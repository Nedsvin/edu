Plataforma Edu

Sistema de gerenciamento de alunos, turmas e matrículas, com autenticação completa de usuários. Desenvolvido com PHP no backend e Vue 3 no frontend.


---

**Tecnologias**

**Backend: PHP, MySQL, JWT**

**Frontend: Vue 3, Vite, Tailwind, Vue Router**

**Ferramentas: Docker, Docker Compose, PhpMyAdmin**

**Bibliotecas auxiliares: Axios, SweetAlert2(para telas de erro e sucesso), Simple Datatables**



---

**Requisitos**

Git (ou Git Bash)
Docker Compose(ou Desktop)

---



**Configuração do Projeto**

O projeto inclui um script de inicialização que configura todo o ambiente automaticamente:

```bash
chmod +x autostart/init.sh
./autostart/init.sh
```

Apenas no caso de algum erro e o banco não popule, ou não seja criado, por favor utilizar:

```bash
docker exec -i edu-mysql mysql -u root -proot < database/dump.sql
docker exec -i edu-mysql mysql -u root -proot edu_db < database/seeds/initial.sql
```

O script realiza:

Inicialização dos containers Docker, importação do banco de dados, seeders, configuração dos arquivos .env

---
**Acesso à Plataforma**

Usuários de teste

| E-mail                    | Senha             |
|---------------------------|-------------------|
| admin@admin.com           | Testando123@      |
| dono@dono.com             | Testando123@      |
| tecnico@tecnico.com       | Testando123@      |


---
**URL de acesso**
http://localhost


---

**API**

A documentação da API está disponível em api/doc.

---

**Postman**

Uma coleção Postman completa está disponível em api/postman/Edu.postman_collection.
Antes de executar outras rotas, utilize a rota de login para gerar o token de autenticação, o arquivo postman possui um script que ira gerar o token para todas outras rotas.

---
**OBS**

Fiz meu back pensando na estrutura de um framework, porém, em PHP puro
Usado como inspiração pontos como

Domain-Driven Design (DDD)
, SOLID
, MVC
, Exception Handling
, Routing
e Validation Layer

Criei funções para o tratamento de rotas, bem como arquivos de Exceptions principais e secundários. Implementei validações abrangentes para garantir a integridade dos dados e o bom funcionamento do sistema.

Desenvolvi também seeders básicos, uma estrutura de Controllers (ainda um pouco arcaica), além de Entities, Utils, Services, Repositories e suas respectivas Implementations, entre outros componentes.

No front-end, utilizei uma estrutura básica com Tailwind CSS e Vue onde monta um template customizado que exibem telas parecidas com dash, porém, toda a parte de validação e integração de dados foi desenvolvida por mim, de forma própria e personalizada.
