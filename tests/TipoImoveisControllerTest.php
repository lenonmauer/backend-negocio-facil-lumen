<?php
class TipoImoveisControllerTest extends TestCase
{
  public function test_get_tipo_imoveis_should_return_status_200()
  {
    $response = $this->get('/v1/tipo-imoveis');

    $this->assertResponseStatus(200);
  }
}
