<?php
use Tests\Concern\AttachJwtToken;

class InteresseTrocaControllerTest extends TestCase
{
  use AttachJwtToken;

  public function test_post_imovel_should_return_status_201()
  {
    $this->disableExceptionHandling();

    $data = [
      'imovel_meu' => [
        'tipo_imovel' => 1,
        'categoria_imovel' => 'NOVO',
        'loc_lat' => '12',
        'loc_lng' => '12',
        'quantidade_quartos' => 2,
        'vagas_garagem' => 2,
        'medida_m2' => 2
      ],
      'imovel_interesse' => [
        'tipo_imovel' => 1,
        'categoria_imovel' => 'USADO',
        'loc_lat' => 12,
        'loc_lng' => 13,
        'quantidade_quartos' => 2,
        'vagas_garagem' => 2,
        'medida_m2' => 2
      ]
    ];

    $response = $this->post('/v1/interesse-troca/imovel', $data);

    $this->assertResponseStatus(201);
  }
}
