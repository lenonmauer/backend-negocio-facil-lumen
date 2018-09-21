<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\InteresseTrocaImovelRepository;
use App\Repositories\ImovelRepository;
use App\Models\Imovel;
use Illuminate\Validation\Rule;
use Validator;

class InteresseTrocaController extends Controller
{
  public function postImovel(
    Request $request,
    InteresseTrocaImovelRepository $interesseRepo,
    ImovelRepository $imovelRepo
  ) {
    $imovel_meu = $request->get('imovel_meu', []);
    $imovel_interesse = $request->get('imovel_interesse', []);

    if (($error = $this->validateImovel($imovel_meu, 'Meu Imóvel')) !== true) {
      return response()->json(['message' => $error], 400);
    }

    if (($error = $this->validateImovel($imovel_interesse, 'Imóvel de meu interesse')) !== true) {
      return response()->json(['message' => $error], 400);
    }

    $user = $request->auth;

    $imovel_meu['user_id'] = $user->id;
    $imovel_interesse['user_id'] = $user->id;

    $imovel_meu = $imovelRepo->create($imovel_meu);
    $imovel_interesse = $imovelRepo->create($imovel_interesse);

    $data = $request->all();
    $data['imovel_meu_id'] = $imovel_meu->id;
    $data['imovel_interesse_id'] = $imovel_interesse->id;
    $data['user_id'] = $user->id;

    $interesseRecord = $interesseRepo->create($data);

    $pageData = [
      'interesse' => $interesseRecord->toArray()
    ];

    return response()->json($pageData, 201);
  }

  public function putImovel(
    Request $request,
    InteresseTrocaImovelRepository $interesseRepo,
    ImovelRepository $imovelRepo,
    $interesse_id
  ) {
    $user = $request->auth;
    $interesseOldRecord = $interesseRepo->getByIdAndUser($interesse_id, $user->id);
    $imovel_meu = $request->get('imovel_meu', []);
    $imovel_interesse = $request->get('imovel_interesse', []);

    if (empty($interesseOldRecord)) {
      return response()->json(['message' => 'Registro não encontrado'], 400);
    }

    if (($error = $this->validateImovel($imovel_meu, 'Meu Imóvel')) !== true) {
      return response()->json(['message' => $error], 400);
    }

    if (($error = $this->validateImovel($imovel_interesse, 'Imóvel de meu interesse')) !== true) {
      return response()->json(['message' => $error], 400);
    }

    $imovel_meu = $imovelRepo->updateById($interesseOldRecord->imovel_meu->id, $imovel_meu);
    $imovel_interesse = $imovelRepo->updateById($interesseOldRecord->imovel_interesse->id, $imovel_interesse);

    $data = $request->all();
    $data['imovel_meu_id'] = $imovel_meu->id;
    $data['imovel_interesse_id'] = $imovel_interesse->id;
    $data['user_id'] = $user->id;

    $interesseNewRecord = $interesseRepo->updateById($interesseOldRecord->id, $data);

    $pageData = [
      'interesse' => $interesseNewRecord->toArray()
    ];

    return response()->json($pageData, 200);
  }

  public function deleteImovel(
    InteresseTrocaImovelRepository $interesseRepo,
    ImovelRepository $imovelRepo,
    $interesse_id
  ) {
    $user = $request->auth;
    $interesse = $interesseRepo->getByIdAndUser($interesse_id, $user->id);

    if (empty($interesse)) {
      return response()->json(['message' => 'Registro não encontrado'], 400);
    }

    $imovelRepo->deleteById($interesse->imovel_meu->id);
    $imovelRepo->deleteById($interesse->imovel_interesse->id);
    $interesseRepo->deleteById($interesse->id);

    return response()->json($pageData, 200);
  }

  private function validateImovel($data, $prefix)
  {
    $inCategorias = Rule::in([Imovel::CATEGORIA_IMOVEL_NOVO, Imovel::CATEGORIA_IMOVEL_USADO]);

    $rules = [
      'tipo_imovel' => 'required|exists:tipo_imoveis,id',
      'categoria_imovel' => ['required', $inCategorias],
      'loc_lat' => 'required|numeric',
      'loc_lng' => 'required|numeric',
      'quantidade_quartos' => 'required|integer',
      'vagas_garagem' => 'required|integer',
      'medida_m2' => 'required|numeric'
    ];

    $messages = [
      'tipo_imovel.required' => 'O campo "' . $prefix . ' - Tipo de imóvel" é obrigatório',
      'tipo_imovel.exists' => 'O valor do campo "' . $prefix . ' - Tipo de imóvel" é inválido',
      'categoria_imovel.required' => 'O campo "' . $prefix . ' - Categoria do imóvel" é obrigatório',
      'categoria_imovel.in' => 'O valor do campo "' . $prefix . ' - Categoria do imóvel" é inválido',
      'loc_lat.required' => 'O campo "' . $prefix . ' - Localização - lat" é obrigatório',
      'loc_lat.numeric' => 'O valor do campo "' . $prefix . ' - Localização - lat" é inválido',
      'loc_lng.required' => 'O campo "' . $prefix . ' - Localização - lng" é obrigatório',
      'loc_lng.numeric' => 'O valor do campo "' . $prefix . ' - Localização - lng" é inválido',
      'quantidade_quartos.required' => 'O campo "' . $prefix . ' - Quantidade de quartos" é obrigatório',
      'quantidade_quartos.integer' => 'O valor do campo "' . $prefix . ' - Quantidade de quartos" é inválido',
      'vagas_garagem.required' => 'O campo "' . $prefix . ' - Vagas na garagem" é obrigatório',
      'vagas_garagem.integer' => 'O valor do campo "' . $prefix . ' - Vagas na garagem" é inválido',
      'medida_m2.required' => 'O campo "' . $prefix . ' - Medida em m²" é obrigatório',
      'medida_m2.numeric' => 'O valor do campo "' . $prefix . ' - Medida em m²" é inválido'
    ];

    $validator = Validator::make($data, $rules, $messages);

    if ($validator->fails()) {
      return $validator->errors()->first();
    } else {
      return true;
    }
  }
}
