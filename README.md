# API Docs

### Authentication Routes
* Login (https://domain.domain/api/login) ( Post Method )
* Register (https://domain.domain/api/register) ( Post Method )
* Get user info (https://domain.domain/api/user) (Get Method)

### Todo Routes
* Get all user todo (https://domain.domain/api/todo) ( Get Method )
* Store todo (https://domain.domain/api/todo) ( Post Method )
* Edit todo (https://domain.domain/api/todo/{id}) ( Put Method )
* Delete todo (https://domain.domain/api/todo/{id}) ( Delete Method )

#### /login Requirement
- email
- password ( min : 8)

#### /register Requirement
- username ( min : 3 )
- email ( unique: Users,email )
- password ( min : 8)

#### /user Requirement
- Authorization = bearer token

#### /todo Requirement
- Get
    - Authorization = bearer token
    - none
- Post
    - Authorization = bearer token
    - content = ( String )
- Put ( id )
    - Authorization = bearer token
    - content = ( String )
- delete ( id )
    - Authorization = bearer token
    - none
