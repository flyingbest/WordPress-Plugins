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

Here is a [**Slides Repository**](http://blog.changwoo.pe.kr/wordpress-plugin-development/).

### Entry Points

Server only responds when it is requested. (- url) Requested moment -> entry point.

1. Menu Items  
 add\_menu\_page(), add\_submenu\_page()  
 remove\_menu\_page(), remove\_submenu\_page()  

 It a CMS. Not a management.

 Where is dafault menu? 
 ```bash
 $ pwd
 wp-admin/menu-header.php
 ```

2. Shortcodes  
 A *magic word* in post content that is replaced by some process defined in plugin or themes in runtime.  
 callback params: ($attrs, $content, $tag).

3. Admin-post  

4. AJAX  

5. Redirect  

6. (de)activation, uninstall  

### Training URL 

Post title : Entry point testing  
Post content : [taeyoon\_shortcode]  

Entering URL :  
 1. Permalink  
 2. (ip)/?&rocket=taeyoon

### Custom Post

classification, reuse

next time : study deep inside about custom post. 

if you have time : blog -> wordpress development -> read about custom post parameter..

## '#4

### Custom Post

You want to manage your own contents. That's why custom post type is:  
 - music collections  
 - online store  
 - event calendar  
 - book data  
 - photo portfolio  

### Practice Custom post

Create custom-post-taeyoon.php

### Roles & Capability

Granting privileges.

Role: a capability preset.  
Capability: a task.

This also related in our training environment. we can access wordpress admin accout and use it. We can check user section of admin screen.

Primitive Capabilities:  
 - Assigned to user roles.  
 - Generally plural.  
 - Always true of false, it is frozen.  

Meta Capabilities:  
 - Dynamically generated by context.   
 - Generally singular.  
 - Added to primitive cap after map\_meta\_cap  

```bash
$ pwd
wp-include/capability.php  // map\_meta\_cap 
```

prevention of side effects -> edit\_post, edit\_page

### Custom Post - Roles & Capabilities

Read this [Blog Post](http://blog.changwoo.pe.kr/%ED%8A%B9%EC%A0%95-%ED%8F%AC%EC%8A%A4%ED%8A%B8%EC%9D%98-%EA%B6%8C%ED%95%9C%EC%9D%84-%EC%A0%9C%EC%96%B4%ED%95%98%EB%8A%94-%EB%A0%88%EC%8B%9C%ED%94%BC/).

Let's assume there are two posts that made by user A, B(post A and post B).  
 - user A made a post A and only ze can access.  
 - user B made a post B and only ze can access.  

post A can NOT be modified by user B, and user B also can NOT modified post B.  
This post A and B are POST. So, we need to more specific capabilities. That's why we use Roles & Capability.

## '#5

### Recap

### 

nonce - wordpress protection.  
checking wordpress form.

