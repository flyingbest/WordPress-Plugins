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

```c
#include <stdio.h>

void A(){
	printf("Hello!\n");
}

void B(void (*ptr) ()){	//function pointer as argument
	ptr();	//call-back function that "ptr" point to
}

int main(){
	//void (*p)() = A;
	//B(p);
	B(A);	//A is callback function.
	return 0;
}
```

> A callback is any executable code that is passed as an argument to other code, which is expected to call back (execute) the argument at a given time. This execution may be immediate as in a synchronous callback, or it might happen at a later time as in an asynchronous callback. 
> \- Referenced by wikipedia.  

### Training dir/files
 
Basic plugin pwd :

```bash
$ pwd
/var/www/html/wp-content/plugins/
```

01\_hello-world/taeyoon-hello-world.php

### GOAL!

- Plan your new plugin idea.

## '#2

Very important!!! **Basic concepts!**

### Training dir/files

### Hooks in Nutshell

Hook-driven programming. A hook means an event.

- Hello dolly's case :  
 [admin notices codex](https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices)  
 [admin head codex](https://codex.wordpress.org/Plugin_API/Action_Reference/admin_head)  

 Find them in the source codes. with following instructions :  
 ```shell
 $ fgrep -Rn do_action\(\ \'admin_notices\'\ \) *
 $ fgrep -Rn do_action\(\ \'admin_head\'\ \) *
 ```

### Hook interface

wp-includes/plugin.php

- | - 
-----|-----
add\_action() | add\_filter() 
has\_action() | has\_filter() 
do\_action() | apply\_filters()

- TaskdSeparation  
 themes : visual part  
 plugins : functional part  

 action - structural part  
 filter - information part  

```php
$ cat wp-includes/plugin.php
...
function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1){
	return add_filter($tag, $function_to_add, $priority, $accepted_args);
}
...
```

As a result, add\_action == add\_filters

add\_action() is mere an alias of add\_filter()
why add\_action() is created?
**because they want to maintain detailed conception.**

### do\_action, add\_action feedback

do\_action:	fires registered action  
add\_action:	loads any actions

No restriction, you can define your own hook.

### call\_user\_finc

PHP function for callback. Formats of input, and output are already defined. But their processes are not clear. Check call back function like this :  

```bash
$ php hello-taeyoon.php
Hello, taeyoon!
Hello, callback!
Hello, World!
```

### database description

reference - [codex.wordpress.org/Database\_Description](https://codex.wordpress.org/Database_Description)

### meta table

Every \*meta table has meta\_key, meta\_value. closely related to 'hashing'.

### Hashing

key - value pair. key is short / value is often long.

### Terms and Taxonomies

Terms : a word.  
Taxonomies : ex) home appliance, metal, iron man.

### Adding Terms in Posts

A Post has basically two taxonomies:  
 - tags  
 - categories  

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
term\_ralationship : posts - taxonomy

### Try it

업무 일지 플러그인
 - 일일 업무를 워드프레스에서 관리
 - 일지는 관련 회원들에게 이메일로 발송, 공유

도서 예약 플러그인
 - 도서 검색
 - 회원이 예약
 - 도서 대출 및 확인

### Helpful Site

This is helpful site. Referenced.

[Plugin Handbook](https://developer.wordpress.org/plugins/).

## '#3

### Recap

Hooks in Nutshell  
 - hook is an event  
 - Plugin is hook-driven  
 - Task separation. (action, filter)  
 - Callback function utility  
 - you can define your own hooks  

Database Nutshell  
 - Parent-meta strategy. ( meta key, meta-value )  
 - Term, taxonomy. (category, tag)  

[**Slides Repository**](http://blog.changwoo.pe.kr/wordpress-plugin-development/)

### Entry Points

Server only responds when it is requested. (- url)

1. Menu Items  
 add\_menu\_page()  
 add\_submenu\_page()  

 It a CMS. Not a management.

 Where is dafault menu? 
 ```bash
 $ pwd
 wp-admin/menu-header.php
 ```

2. Shortcodes  
 callback params: ($attrs, $content, $tag).

3. Admin-post  

4. AJAX  

5. Redirect  

6. (de)activation, uninstall  

test url - http://192.168.30.8/?page\_id=1120&rocket=taeyoon

### Custom Post

classification, reuse

next time : study deep inside about custom post. 

if you have time : blog -> wordpress development -> read about custom post parameter..

## '#4

###

## '#5

###
