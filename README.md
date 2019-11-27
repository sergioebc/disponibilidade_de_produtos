# Disponibilidade de Produtos

API REST com Laravel 6.2

### API
A idéia inicial desta API é oferecer suporte a um sistema para disponibilidade de produtos por distribuidores.

O administrador do sistema cadastra todos os produtos, e distribuidores.

Cada distribuidor cadastrar um preço para os produtos que disponibiliza.

Com isso, o sistema consegue comparar preços entre distribuidores e informar qual deles tem o melhor, dado uma lista de produtos.

### Característica

- CRUD [Usuário, Produto, Distribuidor];
- Seeds e factories de [Produto e Usuário];
- Seed de Usuário com Roles de [Administrador e Vendedor];
- Autenticação com JWT utilizando a biblioteca [tymon/jwt-auth]
- Controle do sistema baseado em Role
- Filtros na busca do [Produto];
- Request para validação do [Produto];
- Possibilidade do [Distribuidor] cadastrar e atualizar uma lista de [Produto] relacionados a ele;
- Possibilidade do [Produto] cadastrar e atualizar uma lista de [Imagem] relacionado a ele;

### Uml base

![uml_disponibilidade_de_produtos](https://user-images.githubusercontent.com/16586749/69754860-b2dd2e00-1135-11ea-8875-7dd5b07f8293.jpeg)
