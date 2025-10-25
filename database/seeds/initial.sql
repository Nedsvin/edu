SET NAMES 'utf8mb4';

INSERT INTO users (name, email, password, role)
VALUES
  ('Admin', 'admin@admin.com', '$2y$10$67SeglkydZQOUFZjPTtX4euhYtdm6F3Ch296/rJZf3z5Gyo3oyjy6', 'admin'),
  ('Dono', 'dono@dono.com', '$2y$10$67SeglkydZQOUFZjPTtX4euhYtdm6F3Ch296/rJZf3z5Gyo3oyjy6', 'admin'),
  ('Técnico', 'tecnico@tecnico.com', '$2y$10$67SeglkydZQOUFZjPTtX4euhYtdm6F3Ch296/rJZf3z5Gyo3oyjy6', 'admin');

-- Senha: "Testando123@"
INSERT INTO students (name, email, password, birth, cpf)
VALUES
  ('André Gomes', 'joao@gmail.com', '$2y$10$67SeglkydZQOUFZjPTtX4euhYtdm6F3Ch296/rJZf3z5Gyo3oyjy6', '1999-02-21', '32499127007'),
  ('Ana Paula Souza', 'anapaula@gmail.com', '$2y$10$67SeglkydZQOUFZjPTtX4euhYtdm6F3Ch296/rJZf3z5Gyo3oyjy6', '2000-04-20', '60942005007'),
  ('Beatriz Rocha', 'rocha@gmail.com', '$2y$10$67SeglkydZQOUFZjPTtX4euhYtdm6F3Ch296/rJZf3z5Gyo3oyjy6', '2001-11-25', '61455997072'),
  ('Juliana Costa', 'juliana@gmail.com', '$2y$10$67SeglkydZQOUFZjPTtX4euhYtdm6F3Ch296/rJZf3z5Gyo3oyjy6', '2003-02-15', '06889963024'),
  ('Larissa Almeida', 'larissa@gmail.com', '$2y$10$67SeglkydZQOUFZjPTtX4euhYtdm6F3Ch296/rJZf3z5Gyo3oyjy6', '1998-11-09', '120066760322'),
  ('Lucas Martins', 'martins@gmail.com', '$2y$10$67SeglkydZQOUFZjPTtX4euhYtdm6F3Ch296/rJZf3z5Gyo3oyjy6', '2003-01-11', '30067176003'),
  ('Xaveco Gabriel', 'xaveco@gmail.com', '$2y$10$67SeglkydZQOUFZjPTtX4euhYtdm6F3Ch296/rJZf3z5Gyo3oyjy6', '2001-06-29', '11533740054');

INSERT INTO classes (name, description)
VALUES
  ('Administração de sistemas', 'Tecnologo em administração de sistemas'),
  ('Ciência da Computação', 'Bacharelado de computação'),
  ('Engenharia de Software', 'Bacharelado de engenharia');

INSERT INTO registrations (student_id, class_id) VALUES (1, 1), (2, 1), (3, 3), (4, 3), (5, 2), (6, 1), (7, 1);