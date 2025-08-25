<?php

namespace Snr\Testovoe;

/*
	task
	1. Напишите функцию подготовки строки, которая заполняет шаблон данными из указанного объекта
	2. Пришлите код целиком, чтобы можно его было проверить
	3. Придерживайтесь code style текущего задания
	4. По необходимости - можете дописать код, методы
	5. Разместите код в гите и пришлите ссылку
*/

/**
 * Класс для работы с API
 *
 * @author		Semakhin Nikita
 * @version		v.1.0 (25/08/2025)
 */
class Api
{
  /**
   * @var array
   */
  protected $allowedProperties;

  /**
   * @var string
   */
  protected $idProperty;

  /**
   * @var string
   */
  protected $apiPrefix;

  public function __construct(string $id_property = 'id', array $necessary_properties = ['name'], $api_prefix = 'api/items')
  {
    if ($this->match_property_name($id_property))
      $this->idProperty = $id_property;
    else throw new \InvalidArgumentException('Ключ идентификатора должнен быть строкой, 
    состоящей из строчных символов (a-z), может содержать нижние подчёркивания');

    foreach ($necessary_properties as $property)
    {
      if (!$this->match_property_name($property))
        throw new \InvalidArgumentException('Ключ любого свойства должен быть строкой, 
        состоящей из строчных символов (a-z), может содержать нижние подчёркивания');
    }
    $this->allowedProperties = $necessary_properties;

    $this->apiPrefix = $api_prefix;
  }

  /**
   * Возвращает true, если имя свойства соответствует шаблону
   *
   * @param string $property_name Имя свойства
   * @return bool
   */
  protected function match_property_name(string $property_name) : bool
  {
    return preg_match("/^{$this->get_property_name_pattern()}$/i", $property_name);
  }

  /**
   * @return string
   */
  protected function get_property_name_pattern() : string
  {
    return "[a-z]([a-z_]{0,30}[a-z])";
  }

  /**
   * Заполняет строковый шаблон template данными из объекта object
   *
   * @author		Semakhin Nikita
   * @version		v.1.0 (25/08/2025)
   * @param     array $array
   * @param     string $template
   * @return		string
   */
  public function get_api_path(array $array, string $template) : string
  {
    $properties = $array;
    unset($properties[$this->idProperty]);

    if (empty($array[$this->idProperty]) || (int) $array[$this->idProperty] != $array[$this->idProperty])
      throw new \Exception("Не установлено значение свойства идентфикатора ('{$this->idProperty}').");

    // api/items => api\\/items
    $prefix_for_pattern = str_replace('/', '\\/', $this->apiPrefix);
    // ['name', 'role', 'salary'] => (name|role|salary)
    $properties_for_pattern = '(' . implode('|', $this->allowedProperties) . ')';

    $pattern = "/^\\/{$prefix_for_pattern}\\/%{$this->idProperty}%\\/%$properties_for_pattern%$/i";
    $matches = [];
    if (!preg_match($pattern, $template, $matches))
      throw new \Exception("Шаблон '$template' не соответствует формату api. Формат api - " .
        "{$this->apiPrefix}/{$this->idProperty}/{ключ_свойства}");
    $property_name = $matches[1];

    if (!in_array($property_name, $this->allowedProperties))
      throw new \Exception("Свойство объекта '$property_name' не предусмотрено");

    if (!isset($array[$property_name]))
      throw new \InvalidArgumentException("Свойство объекта '$property_name' не установлено");

    $result = str_replace("%{$this->idProperty}%", $array[$this->idProperty], $template);
    return str_replace("%{$property_name}%", $array[$property_name], $result);
  }
}

