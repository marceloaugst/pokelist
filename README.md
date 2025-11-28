#  Sistema de Gerenciamento de Pokémon

Sistema desenvolvido em Laravel para gerenciar sua coleção de Pokémons capturados em diferentes jogos, com integração à PokéAPI e comparação de stats.

##  Funcionalidades

-  **Sistema de Autenticação**: Login e registro de usuários (Laravel Breeze)
-  **Lista de Pokémons**: Visualize todos os Pokémons que você capturou
-  **Adicionar Pokémon**: Busque por ID na PokéAPI e adicione à sua coleção
-  **Comparação de Stats**: Compare os stats base da PokéAPI com os stats do seu Pokémon no jogo
-  **Análise Visual**: Indicadores visuais (verde/amarelo/vermelho) mostram se os stats estão bons
-  **Dados Individuais**: Cada usuário tem sua própria lista de Pokémons
-  **Múltiplos Jogos**: Registre de qual jogo cada Pokémon veio

##  Tecnologias Utilizadas

- **Laravel 12** - Framework PHP
- **Laravel Breeze** - Autenticação
- **MySQL** - Banco de dados
- **Tailwind CSS** - Estilização
- **PokéAPI** - Dados dos Pokémons
- **Blade Templates** - Views

##  Instalação

O projeto já está configurado e pronto para uso!

### Banco de Dados

Configurado em `.env`:
- **Host**: pokelist.mysql.dbaas.com.br
- **Database**: pokelist
- **Username**: pokelist
- **Password**: M@rc992830745

As migrations já foram executadas e as tabelas criadas.

### Servidor

O servidor está rodando em: **http://127.0.0.1:8000**

##  Como Usar

### 1. Criar uma Conta

1. Acesse http://127.0.0.1:8000
2. Clique em "Register"
3. Preencha seus dados e crie sua conta

### 2. Adicionar um Pokémon

1. Faça login
2. Clique em "Adicionar Pokémon"
3. Digite o ID do Pokémon (1-1025)
4. Clique em "Buscar" - os dados da PokéAPI serão carregados
5. Preencha:
   - Nome do jogo (ex: "Pokémon Red", "Pokémon Sword")
   - Nível do Pokémon
   - Stats do jogo (HP, Attack, Defense, etc.)
6. Opcionalmente, adicione notas
7. Clique em "Adicionar Pokémon"

### 3. Visualizar Seus Pokémons

Na página "Meus Pokémons" você verá:
- Imagem do Pokémon
- Nome e jogo de origem
- Comparação visual de cada stat
- Porcentagem: Stats do jogo / Stats base da PokéAPI
- Cores indicativas:
  -  Verde: 80% (Bom)
  -  Amarelo: 60-79% (Médio)
  -  Vermelho: <60% (Fraco)

### 4. Ver Detalhes

Clique em "Ver Detalhes" para uma análise completa:
- Visualização detalhada de cada stat
- Comparação lado a lado (Base vs Jogo)
- Suas notas sobre o Pokémon
- Data de adição

##  Estrutura do Projeto

```
app/
 Http/Controllers/
    PokemonController.php      # Controlador principal
 Models/
    User.php                   # Model de usuário
    UserPokemon.php            # Model de pokémon do usuário
 Policies/
    UserPokemonPolicy.php      # Autorização
 Services/
     PokeApiService.php         # Integração com PokéAPI

database/migrations/
 2025_11_28_184221_create_user_pokemons_table.php

resources/views/
 pokemons/
     index.blade.php            # Lista de pokémons
     create.blade.php           # Adicionar pokémon
     show.blade.php             # Detalhes do pokémon

routes/
 web.php                        # Rotas da aplicação
```

##  Rotas Disponíveis

- `GET /` - Redireciona para login
- `GET /login` - Página de login
- `GET /register` - Página de registro
- `GET /pokemons` - Lista de pokémons
- `GET /pokemons/create` - Formulário de adicionar
- `POST /pokemons/search` - Buscar pokémon na PokéAPI
- `POST /pokemons` - Salvar novo pokémon
- `GET /pokemons/{id}` - Ver detalhes
- `DELETE /pokemons/{id}` - Remover pokémon

##  Exemplo de Uso

### IDs de Pokémons Populares:
- 1 - Bulbasaur
- 4 - Charmander
- 7 - Squirtle
- 25 - Pikachu
- 94 - Gengar
- 150 - Mewtwo

##  Comandos Úteis

```bash
# Parar o servidor
Ctrl + C

# Iniciar o servidor novamente
cd C:\Users\THINKPAD\Documents\marcelo\pokemon-manager
php artisan serve

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Ver rotas
php artisan route:list
```

##  Estrutura do Banco de Dados

### Tabela: user_pokemons

| Coluna | Tipo | Descrição |
|--------|------|-----------|
| id | bigint | ID único |
| user_id | bigint | ID do usuário |
| pokemon_id | int | ID da PokéAPI |
| pokemon_name | varchar | Nome do Pokémon |
| game_name | varchar | Nome do jogo |
| sprite_url | varchar | URL da imagem |
| base_hp | int | HP base (PokéAPI) |
| base_attack | int | Ataque base |
| base_defense | int | Defesa base |
| base_sp_attack | int | Ataque Especial base |
| base_sp_defense | int | Defesa Especial base |
| base_speed | int | Velocidade base |
| game_hp | int | HP no jogo |
| game_attack | int | Ataque no jogo |
| game_defense | int | Defesa no jogo |
| game_sp_attack | int | Ataque Especial no jogo |
| game_sp_defense | int | Defesa Especial no jogo |
| game_speed | int | Velocidade no jogo |
| level | int | Nível do Pokémon |
| notes | text | Observações |
| created_at | timestamp | Data de criação |
| updated_at | timestamp | Data de atualização |

##  Recursos Especiais

- **Isolamento por Usuário**: Cada usuário vê apenas seus próprios pokémons
- **Validação de Stats**: Garante que os valores inseridos sejam válidos
- **Autorização**: Políticas de segurança impedem acesso não autorizado
- **Interface Responsiva**: Funciona em desktop e mobile
- **Integração Real-time**: Busca dados atualizados da PokéAPI

##  Troubleshooting

### Erro de Conexão com Banco de Dados
Verifique se as credenciais em `.env` estão corretas.

### PokéAPI não responde
Verifique sua conexão com a internet. A PokéAPI é externa.

### Erro 404
Execute `php artisan route:cache` para atualizar as rotas.

---

Desenvolvido com  usando Laravel e PokéAPI
