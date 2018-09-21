<?php
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\Concern\AttachJwtToken;
use Illuminate\Support\Facades\DB;
use App\Models\City;
use App\Models\Profile;
use App\Models\SellerProfile;

class ProfileControllerTest extends TestCase
{
  use AttachJwtToken;

  public function test_put_profile_should_return_status_200_with_minimum_data()
  {
    $data = [
      'nome' => 'Lenon Mauer',
      'cpf' => '03091411095',
      'data_nascimento' => '21/03/1993'
    ];

    $this->put('/v1/user/profile', $data);

    $this->assertResponseStatus(200);
  }

  public function test_put_profile_should_return_status_200_with_full_data()
  {
    $city = DB::table('cities')->first();
    $faker = Faker\Factory::create();

    $data = [
      'nome' => 'Lenon Mauer',
      'cpf' => '03091411095',
      'data_nascimento' => '21/03/1993',
      'city_id' => $city->id,
      'profissao' => $faker->name,
      'phone_1' => $faker->phoneNumber,
      'phone_2' => $faker->phoneNumber,
      'logradouro' => $faker->name,
      'numero' => $faker->randomNumber(3),
      'bairro' => $faker->name,
      'cep' => '95590-000',
      'estado_civil' => Profile::ESTADO_CIVIL_CASADO
    ];

    $this->put('/v1/user/profile', $data);

    $this->assertResponseStatus(200);
  }

  public function test_post_seller_profile_should_return_status_201_with_full_data()
  {
    $faker = Faker\Factory::create();
    $city = DB::table('cities')->first();

    $data = [
      'nome' => $faker->name,
      'cpf' => '03091411095',
      'city_id' => $city->id,
      'tipo_pessoa' => SellerProfile::TIPO_PESSOA_FISICA,
      'cnpj' => '02845132000140',
      'resumo' => $faker->text(),
      'site' => $faker->url,
      'phone_1' => $faker->phoneNumber,
      'phone_2' => $faker->phoneNumber,
      'logradouro' => $faker->name,
      'numero' => $faker->randomNumber(3),
      'bairro' => $faker->name,
      'cep' => '95590000'
    ];

    $this->post('/v1/user/seller-profile', $data);

    $this->assertResponseStatus(201);
  }
}
