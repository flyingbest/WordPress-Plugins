# WordPress-Plugins

## '#1

### Requirements

- PHP 5.4 programming skill. Wordpress is PHP application. function, callback, class. (at least)
- mysql 5.6+ (also know about WordPress DataBase), and query instructions.
- Client-Side language : HTML, Javascript, CSS.

### PHP concepts

PHP programming concepts: function, class

```
function f(x) = x^2
function g(x) = x+1
```

**input, output, function(processing)**

```php
function f($x) { return $x * $x; }
function g($x) { return $x + 1; }
```

(option) type hinting is available.

### advanced : class

Same as CPP concepts. Class. (inheritance, object...)

### Callback Function

Plugins extend WordPress. threr is a 'protocol'. **Imagine the protocol.** Wordpress core knows every possible situation. I throw it at this time to do something. Then, Core is executed in accordance with To-Do List.

In practice examples :

```
function(){
	echo
}  // this is callback function.
```

> A callback is any executable code that is passed as an argument to other code, which is expected to call back (execute) the argument at a given time. This execution may be immediate as in a synchronous callback, or it might happen at a later time as in an asynchronous callback. 
> \- Referenced by wikipedia.  

### Environments

rocketeer 5G 192.168.1.22
<!-- wordpress / 1 -->
taeyoon-hello-world.php

### GOAL!

- Plan your new plugin idea.

## '#2

Very important!!! **Basic concepts!**

### Environments

using ssh.  
<!-- wordpress / 1 -->  
/var/www/wordpress/wp-content/plugins/call\_user\_func

### Hooks in Nutshell

A hook means an event.

admin notices codex  
admin head codex

```shell
fgrep -Rn do_action\(\ \'admin_notices\'\ \) *
```

### Hook interface

wp-includes/plugin.php

-----|-----
add\_action() | add\_filter() 
has\_action() | has\_filter() 
do\_action() | apply\_filters()

themes : visual part  
plugins : functional part

action - structural part  
filter - information part

add\_action == add\_filter

add\_action() is mere an alias of add\_filter()
why add\_action() is created?
*because they want to maintain detailed conception.*

### do\_action, add\_action feedback

do\_action:	fires registered action  
add\_action:	loads any actions

No restriction, you can define your own hook.

### call\_user\_finc

### database description

parent - meta styles  
reference - [codex.wordpress.org/Database\_Description](https://codex.wordpress.org/Database_Description)

### meta table

### Hashing

key - value pair.

### Terms and Taxonomies

Terms : a word.  
Taxonomies : ex) home appliance, metal, iron man.

### Adding Terms in Posts

Adding tag/category == adding terms

Post id -> term\_relationships.term\_taxonomy\_id -> term\_taxonomy.term\_id -> terms.term\_id

### Summary

Wordpress Database table uses metadata style strategy.

meta tables have key / value fields
 - flexible, easy to extend
 - while it can have some disadvantages.

taxonomy : classification of term  
you can add taxonomies (as well as trems)  
taxonomy can be flat or hierarchical.  

term\_taxonomy : taxonomy - term  
term\_ralationship : post - taxonomy

### Try it

업무 일지 플러그인
 - 일일 업무를 워드프레스에서 관리
 - 일지는 관련 회원들에게 이메일로 발송, 공유

도서 예약 플러그인
 - 도서 검색
 - 회원이 예약
 - 도서 대출 및 확인

