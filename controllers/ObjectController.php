<?php
require_once "TwigController.php";

class ObjectController extends TwigController
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

	public function post(array $context)
	{
		if ($_SESSION['stud'] == 0) {
			$id_p = $_GET['project'];
			$id_q_range = $_POST['range'];
			$id_q_text = $_POST['text'];
			$sql = <<<EOL
INSERT INTO form (id_p, id_q)
VALUES (:id_p, :id_q_range);
INSERT INTO form (id_p, id_q)
VALUES (:id_p, :id_q_text);
EOL;
			$query = $this->pdo->prepare($sql);
			$query->bindValue("id_p", $id_p);
			$query->bindValue("id_q_range", $id_q_range);
			$query->bindValue("id_q_text", $id_q_text);
			$query->execute();
		}

		if ($_SESSION['stud'] == 1) {
			$query = $this->pdo->query("SELECT id FROM form WHERE id_p = " . $_GET['project']);
			$context['form_id'] = $query->fetchAll();
			$answer_range = $_POST['range'];
			$answer_text = $_POST['text'];
			$sql = <<<EOL
INSERT INTO answer (id_form, answer)
VALUES (:id_form_f, :answer_range);
INSERT INTO answer (id_form, answer)
VALUES (:id_form_s, :answer_text);
EOL;
			$query = $this->pdo->prepare($sql);
			$query->bindValue("id_form_f", $context['form_id'][0][0]);
			$query->bindValue("id_form_s", $context['form_id'][1][0]);
			$query->bindValue("answer_range", $answer_range);
			$query->bindValue("answer_text", $answer_text);
			$query->execute();
		}

		header("Location: /?role=stud");
		exit;

		$this->get($context);
	}
}
