<?php
require_once "BaseGamesTwigController.php";

class ObjectController extends BaseGamesTwigController
{
	public $template = "__object.twig";

	public function getContext(): array
	{
		$context = parent::getContext();

		if ($_SESSION['stud'] == 0) {
			$context['title'] = 'Формирование анкеты отзыва';
			$context['stud'] = 0;
			$query = $this->pdo->query("SELECT id, question FROM question WHERE value = 1");
			$context['q_o'] = $query->fetchAll();
			$query = $this->pdo->query("SELECT id, question FROM question WHERE value = 0");
			$context['q_t'] = $query->fetchAll();
		} else {
			$context['title'] = 'Формирование отзыва';
			$context['stud'] = 1;
			$query = $this->pdo->query("SELECT id_q FROM form WHERE id_p = " . $_GET['project']);
			$context['questions'] = $query->fetchAll();
			$query = $this->pdo->query("SELECT question FROM question WHERE id = " . $context['questions'][0][0]);
			$context['q_o'] = $query->fetchAll();
			$query = $this->pdo->query("SELECT question FROM question WHERE id = " . $context['questions'][1][0]);
			$context['q_t'] = $query->fetchAll();
		}


		return $context;
	}
}
