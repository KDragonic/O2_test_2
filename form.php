<?php
class Send
{
	private $data;

	public function __construct($data)
	{
		$this->data = $data;

		if ($this->validation($this->data)) $this->send($this->data);
	}

	public function validation()
	{
		$requires = ['email'];

		$response = [
			"message" => "",
			"errors" => []
		];

		foreach ($this->data as $key) {
			if (empty($validate[$key]) && in_array($key, $requires))
				$response["errors"][] = $key;
		}

		if (!empty($response["message"])) {
			$response["message"] = "Заполните обязательное поле";
			echo json_encode($response);
			exit();
		}

		// if (isset($data['phone']))
		// {
		// 	$patterPhone = preg_match("/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/", $data['phone']);
		// 	if (!$patterPhone) {
		// 		$errors["errors"][] = "phone";
		// 	}
		// }

		if (isset($this->data['email']))
		{
			$patterEmail = preg_match("/^[a-z0-9_-]+@[a-z0-9-]+\.([a-z]{1,6}\.)?[a-z]{2,6}$/i", $this->data['email']);
			if (!$patterEmail) {
				$response["errors"][] = "email";
			}
		}

		if (!empty($response["errors"])) {
			$response["message"] = "Не правильный формат";
			echo json_encode($response);
			exit();
		}
		return true;
	}

	public function send($date)
	{

		$link = mysqli_connect("localhost", "root", "root", "test_2");

		if ($link == false) {
			$response["massage"] = 'Ошибка: Невозможно подключиться к MySQL ' . mysqli_connect_error();
			echo json_encode($response);
			exit();
		}

		if($link->query("SELECT * from users WHERE email='{$date["email"]}'")->num_rows == 0)
		{
			$sql = "INSERT INTO users (email) VALUES ('{$date["email"]}')";
			$result = mysqli_query($link, $sql);
		}
		else
		{
			$response["massage"] = "Такая запись уже существует";
			echo json_encode($response);
			exit();
		}

		if ($result == false) {
			$response["massage"] = "Произошла ошибка при выполнении запроса: " . mysqli_error($link);
			echo json_encode($response);
			exit();
		}

		$response = [
			"message" => "Запрос выполнен удачно!",
		];

		echo json_encode($response);
		exit();
	}
}
$send = new Send($_POST);
