# FinControl 💰

Sistema de controle financeiro pessoal desenvolvido em PHP e MySQL, utilizando arquitetura MVC.

## 🚀 Guia de Instalação e Execução

Siga os passos abaixo para rodar o projeto localmente em sua máquina.

### 1. Pré-requisitos
Certifique-se de ter o **XAMPP** instalado (ou qualquer servidor Apache + MySQL equivalente).
- [Download do XAMPP](https://www.apachefriends.org/pt_br/download.html)

### 2. Instalação dos Arquivos
1. Navegue até a pasta de instalação do XAMPP (geralmente `C:\xampp\htdocs`).
2. Crie uma pasta chamada **`fin-control`**.
3. Cole todos os arquivos deste projeto dentro dela.
   - O caminho final deve ficar parecido com: `C:\xampp\htdocs\fin-control\public\...`

### 3. Configuração do Banco de Dados
1. Inicie os módulos **Apache** e **MySQL** no painel do XAMPP.
2. Acesse o **phpMyAdmin** no navegador: [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
3. Clique na aba **"Importar"** (menu superior).
4. Selecione o arquivo **`schema.sql`** localizado na pasta `database/` do projeto.
5. Clique em **Executar**.
   - *Isso criará automaticamente o banco de dados `fin_control` e todas as tabelas necessárias.*

### 4. Verificação de Configuração
Abra o arquivo `config/config.php` e confirme se as configurações padrão do XAMPP estão mantidas:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');            // Senha vazia no XAMPP padrão
define('DB_NAME', 'fin_control');
define('BASE_URL', 'http://localhost/fin-control');



### 5. Como Acessar
Abra seu navegador preferido.

Acesse o endereço: http://localhost/fin-control

Clique em "Cadastre-se gratuitamente" para criar seu primeiro usuário e acessar o sistema.
