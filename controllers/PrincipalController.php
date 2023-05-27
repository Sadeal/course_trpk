<?php
require_once "BaseGamesTwigController.php";

class PrincipalController extends BaseGamesTwigController
{
	public $template = "principal.twig";
	public $title = "Управление пользователями";


	public function getContext(): array
	{
		$context = parent::getContext();

		$query = $this->pdo->query("SELECT id, title FROM project");
		$context['projects'] = $query->fetchAll();
		$query = $this->pdo->query("SELECT id, id_p, id_q FROM form");
		$context['forms'] = $query->fetchAll();
		$query = $this->pdo->query("SELECT id, question FROM question");
		$context['questions'] = $query->fetchAll();
		$query = $this->pdo->query("SELECT id_form, answer FROM answer");
		$context['answers'] = $query->fetchAll();

		return $context;
	}

	public function get(array $context)
	{
		$context['title'] = "Просмотр отзывов";
		parent::get($context);
	}

	public function post(array $context)
	{
		$login = $_POST['login'];
		$type = '';
		$sql = <<<EOL
SELECT type
FROM users
WHERE login = :login
EOL;
		$query = $this->pdo->prepare($sql);
		$query->bindValue("login", $login);
		$query->execute();
		$data = $query->fetch();
		if ($data['type'] == 'admin') {
			$type = 'user';
		} else {
			if ($data['type'] == 'user') {
				$type = 'admin';
			} else {
				$type = 'owner';
			}
		}

		$sql = <<<EOL
UPDATE users
SET type = :type
WHERE login = :login
EOL;
		$query = $this->pdo->prepare($sql);
		$query->bindValue("login", $login);
		$query->bindValue("type", $type);
		$query->execute();

		header("Location: /users/manage");
		exit;

		$this->get($context);
	}
}
