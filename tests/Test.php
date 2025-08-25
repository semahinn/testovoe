<?php

namespace Snr\Testovoe\Tests;

use Snr\Testovoe\Api;

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
  public function test()
  {
    $api_prefix = 'api/items';
    $user =
      [
        'id' => 20,
        'name' => 'John Dow',
        'role' => 'QA',
        'salary' => 100
      ];

    $api_path_templates =
      [
        "/$api_prefix/%id%/%name%",
        "/$api_prefix/%id%/%role%",
        "/$api_prefix/%id%/%salary%"
      ];

    $api = new Api('id', ['name', 'role', 'salary'], $api_prefix);

    $api_paths = array_map(function ($api_path_template) use ($api, $user)
    {
      return $api->get_api_path($user, $api_path_template);
    }, $api_path_templates);

    $expected_result = ['/api/items/20/John Dow','/api/items/20/QA','/api/items/20/100'];
    foreach ($expected_result as $key => $result_value) {
      $api_path_template = $api_path_templates[$key];
      $this->assertArrayHasKey($key, $api_paths, "Отсутствует результат для шаблона '$api_path_template'");
      $this->assertEquals($api_paths[$key], $result_value, "Полученный результат ('{$api_paths[$key]}') 
      не совпадает с требуемым ('$result_value')");
    }
  }
}