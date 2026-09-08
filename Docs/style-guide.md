# guia de estilos

## PHP:
padrão PSR-4/PSR-12
resumo:
### arquivos/pasta:

Uma classe por arquivo;
nome de classes e seus arquivos em PascalCase.
ex: UserLogin.php,UserLogout.php

### código:

Espaçamento:
4 espaços por nível de identação, não use tabs (pode usar a tecla de tab, apenas não o caractere; pesquise como fazer isso no seu editor de codigo)

blocos de código separados por chaves devem ter as chaves "sozinhas".

ex positivo:
``` php
function exemplo()
{
  //codigo fazendo algo
}
```

Classes em PascalCase

ex positivo:
- Produto 
- AnimalSelvagem

Métodos em camelCase

nomes descritivos e seguindos os padrões de php, não repetir o nome da classe dentro do método

pega valor; get, definir valor; set

ex negativo:
- aquireUserId();
- defineUserId();

ex positivo: 
- getId();
- setId();

quando retorna um valor booleano, usar 'has/have' ou 'is', sem negações, ao invés disso, escrever metodos que retornam true caso algo seja/tenha uma condição, e negue eles caso nescessário usando o "!"

ex negativo:
- isNotAdmin();
- doesntHavePreference();

ex positivo:
- isAdmin();
- HasPreference();

Variáveis em camelCase

## HTML:

### Arquivos/pastas:

a discutir. por enquanto:

para cada página, criar uma pasta com a pagina no index, incluindo:
- index.php ou index.html da página
- nome_da_pasta.css
- nome_da_pasta.js, caso nescessário
ex:
``` directory
Login/
├── index.php
├── Login.css
└── Login.js
```

nomear assim 

## CSS:

a discutir

### css reset/base:

pelo menos por enquanto, usar a base de css [MVP.css](../Public/MVP.css) .

linkar para ele como primeiro stylesheet da pagina, fazendo eventuais modificações no arquivo específico para a página atual. usar sempre caminhos relativos para os arquivos.

qualquer duvida estou a disposição
