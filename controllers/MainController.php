<?php
require_once "BaseGamesTwigController.php";

class MainController extends BaseGamesTwigController
{
    public $template = "main.twig";
    public $title = "Главная";

    public function getContext(): array
    {
        $context = parent::getContext();

        if ($_GET['role'] == 'stud') {
            $_SESSION['stud'] = 1;
            $_SESSION['student_id'] = 1;
            $_SESSION['teacher_id'] = 0;
            $query = $this->pdo->query("SELECT id_p FROM student WHERE id = " . $_SESSION['student_id']);
            $context['student_p'] = $query->fetch();
        } else {
            $_SESSION['stud'] = 0;
            $_SESSION['student_id'] = 0;
            $_SESSION['teacher_id'] = 1;
            $query = $this->pdo->query("SELECT id FROM project WHERE id_teacher = " . $_SESSION['teacher_id']);
            $context['teacher_p'] = array_map(fn ($item) => $item[0], $query->fetchAll());
        }
        $query = $this->pdo->query("SELECT id_p FROM form");
        $context['project_with_form'] = array_map(fn ($item) => $item[0], $query->fetchAll());
        $context['stud'] = $_SESSION['stud'];
        $query = $this->pdo->query("SELECT * FROM project");
        $context['projects'] = $query->fetchAll();

        return $context;
    }
}
