<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Repositories\SellerProfileRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\StateRepository;
use App\Repositories\CityRepository;
use App\Models\SellerProfile;
use App\Models\Profile;
use App\Helpers\Format;
use App\Helpers\Sanitize;

class ProfileController extends Controller
{
  public function getMyProfile(Request $request, StateRepository $stateRepo, CityRepository $cityRepo)
  {
    $user = $request->auth;
    $profile = $user->profile;

    $pageData = [
      'profile' => $profile->toArray()
    ];

    if (isset($_GET['states'])) {
      $pageData['states'] = $stateRepo->getAll('name');
    }

    if (isset($_GET['cities']) && !empty($profile->city_id)) {
      $pageData['cities'] = $cityRepo->getAllByStateId($profile->state_id);
    }

    return response()->json($pageData, 200);
  }

  public function getMySellerProfiles(Request $request, StateRepository $stateRepo, CityRepository $cityRepo)
  {
    $user = $request->auth;
    $profiles = $user->seller_profiles;
    $mainProfile = @$profiles[0];

    $pageData = [
      'profiles' => $profiles->toArray()
    ];

    if (isset($_GET['states'])) {
      $pageData['states'] = $stateRepo->getAll('name');
    }

    if (isset($_GET['cities']) && !empty($mainProfile) && !empty($mainProfile->city_id)) {
      $pageData['cities'] = $cityRepo->getAllByStateId($mainProfile->state_id);
    }

    return response()->json($pageData, 200);
  }

  public function putProfile(Request $request, ProfileRepository $profileRepo)
  {
    $data = $request->all();
    $user = $request->auth;
    $profile = $user->profile;

    $messages = [
      'nome.required' => 'O campo "Nome" é obrigatório',
      'city_id.exists' => 'O valor do campo "Cidade" é inválido',
      'foto_file_id.exists' => 'O valor do campo "Foto" é inválido',
      'cep.cep' => 'O valor do campo "CEP" é inválido',
      'numero.numeric' => 'O valor do campo "Número" é inválido',
      'data_nascimento.required' => 'O campo "Data de Nascimento" é obrigatório',
      'data_nascimento.date_br' => 'O valor do campo "Data de Nascimento" é inválido',
      'estado_civil.in' => 'O valor do campo "Estado Civil" é inválido',
      'cpf.required' => 'O campo "CPF" é obrigatório',
      'cpf.cpf' => 'O valor do campo "CPF" é inválido'
    ];

    $rules = [
      'nome' => 'required',
      'city_id' => 'exists:cities,id',
      'cep' => 'nullable|cep',
      'numero' => 'nullable|numeric',
      'cpf' => 'required|cpf',
      'data_nascimento' => 'required|date_br',
      'estado_civil' => 'nullable|' . Rule::in([Profile::ESTADO_CIVIL_SOLTEIRO, Profile::ESTADO_CIVIL_CASADO])
    ];

    $validator = Validator::make($data, $rules, $messages);

    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors()->first()], 400);
    }

    $data['cpf'] = Sanitize::number(@$data['cpf'], false);
    $data['data_nascimento'] = Format::dateBRToISO($data['data_nascimento']);
    $data['phone_1'] = Sanitize::number(@$data['phone_1'], false);
    $data['phone_2'] = Sanitize::number(@$data['phone_2'], false);
    $data['cep'] = Sanitize::number(@$data['cep'], false);

    unset($data['state_id']);

    $profileRepo->updateById($profile->id, $data);
    $user->load('profile');

    $pageData = [
      'user' => [
        'email' => $user->email,
        'nome' => $user->profile->nome,
        'foto_perfil' => $user->profile->foto_facebook_url
      ]
    ];

    return response()->json($pageData, 200);
  }

  public function postSellerProfile(Request $request, SellerProfileRepository $sellerProfileRepo, $profile_id = 0)
  {
    $data = $request->all();
    $user = $request->auth;

    $messages = [
      'nome.required' => 'O campo "Nome" é obrigatório',
      'city_id.exists' => 'O valor do campo "Cidade" é inválido',
      'cep.cep' => 'O valor do campo "CEP" é inválido',
      'numero.numeric' => 'O valor do campo "Número" é inválido',
      'tipo_pessoa.required' => 'O campo "Tipo de Pessoa" é obrigatório',
      'tipo_pessoa.in' => 'O valor do campo "Tipo de Pessoa" é inválido',
      'cpf.required' => 'O campo "CPF" é obrigatório',
      'cpf.cpf' => 'O valor do campo "CPF" é inválido',
      'cnpj.required' => 'O campo "CNPJ" é obrigatório',
      'cnpj.cnpj' => 'O valor do campo "CNPJ" é inválido'
    ];

    $rules = [
      'nome' => 'required',
      'city_id' => 'nullable|exists:cities,id',
      'cep' => 'nullable|cep',
      'numero' => 'nullable|numeric',
      'tipo_pessoa' => ['required', Rule::in([SellerProfile::TIPO_PESSOA_FISICA, SellerProfile::TIPO_PESSOA_JURIDICA])]
    ];

    $validator = Validator::make($data, $rules, $messages);

    $validator->sometimes('cpf', 'required|cpf', function ($input) {
      return $input->tipo_pessoa == SellerProfile::TIPO_PESSOA_FISICA;
    });

    $validator->sometimes('cnpj', 'required|cnpj', function ($input) {
      return $input->tipo_pessoa == SellerProfile::TIPO_PESSOA_JURIDICA;
    });

    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors()->first()], 400);
    }

    $data['cpf'] = Sanitize::number(@$data['cpf'], false);
    $data['cnpj'] = Sanitize::number(@$data['cnpj'], false);
    $data['phone_1'] = Sanitize::number(@$data['phone_1'], false);
    $data['phone_2'] = Sanitize::number(@$data['phone_2'], false);
    $data['cep'] = Sanitize::number(@$data['cep'], false);

    $newProfile = $sellerProfileRepo->updateOrCreate($profile_id, $user->id, $data);

    $pageData = [
      'profile' => $newProfile->toArray()
    ];
    $status = empty($profile_id) ? 201 : 200;

    return response()->json($pageData, $status);
  }

  public function putSellerProfile(Request $request, SellerProfileRepository $sellerProfileRepo, $profile_id)
  {
    return $this->postSellerProfile($request, $sellerProfileRepo, $profile_id);
  }
}
