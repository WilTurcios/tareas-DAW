<?php

interface IModelo
{
  public function save(): array;
  public static function delete(int $id): array;
  public static function get_all(): array;
  public static function get_by_id(int $id): array;
}
