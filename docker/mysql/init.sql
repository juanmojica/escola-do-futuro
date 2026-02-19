-- Script de inicialização do MySQL
-- Este script é executado automaticamente na primeira vez que o container é criado

-- Criar banco de desenvolvimento
CREATE DATABASE IF NOT EXISTS escola CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Criar banco de testes
CREATE DATABASE IF NOT EXISTS escola_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Log de confirmação
SELECT 'Bancos de dados criados com sucesso!' as status;
