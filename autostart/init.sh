#!/bin/bash
cd "$(dirname "$0")/.."

echo "Iniciando a configuração do projeto..."
echo "Checando a existência do arquivo .env no backend..."
cd backend
if [ ! -f .env ]; then
    cp .env.example .env
    echo "Arquivo .env do backend criado com sucesso a partir de .env.example."
else
    echo "Arquivo .env do backend já está presente, nenhuma alteração realizada."
fi

if [ ! -f .env.test ]; then
    cp .env.example .env.test
    echo "Arquivo .env.test do backend criado a partir de .env.example."
else
    echo "Arquivo .env.test do backend já existe."
fi
cd ..

echo "Checando o arquivo .env no frontend..."
cd frontend
if [ ! -f .env ]; then
    cp .env.example .env
    echo "Arquivo .env do frontend criado com sucesso a partir de .env.example."
else
    echo "Arquivo .env do frontend já está presente."
fi
cd ..

echo "Iniciando os containers com Docker Compose..."
docker-compose up -d --build

echo "Instalando dependências via Composer no backend..."
docker exec -it edu-backend composer install --no-interaction --prefer-dist

echo "Configurando o banco de dados..."
docker exec -i edu-mysql mysql -u root -proot < database/dump.sql
echo "Populando o banco com os seeds iniciais..."
docker exec -i edu-mysql mysql -u root -proot edu_db < database/seeds/initial.sql

echo ""
echo "Configuração do projeto concluída com sucesso!"
echo ""
echo "Links Uteis"
echo ""
echo "Acessar via proxy: http://localhost"
echo "Acessar sem proxy: http://localhost:5173"
echo ""
echo "PHPMyAdmin: http://localhost:8081"
echo ""
