Laravel Widget System
=========

- [Installation](#installation)
- [What's New](#whats-new)
- [Registering Widget](#registering-widget)
- [Calling Widget](#calling-widget)
- [Grouping Widget](#grouping-widget)

<a name="installation"></a>
## Installation

Open your composer.json file and add the new required package.

```
    "pingpong/widget" : "2.0.*@dev"
```

Next, open your terminal and run `composer update`.

After composer updated, add new service provider in `config/app.php` :

```php
    'Pingpong\Widget\WidgetServiceProvider',
```

And add facade in the same file

```php
    'Widget' => 'Pingpong\Widget\Facades\Widget'
```

Done.

<a name="whats-new"></a>
## What's New!

Subscribe widget: It's a new way to register widget using a specified class. For example:

```php
Widget::subscribe('WidgetSubscriber');

class WidgetSubscriber {

	public function subscribe($widget)
	{
		$widget->register('image', __CLASS__ .'@image');
	}

	public function image()
	{
		return 'Your handler here';
	}
}
```

You can also specified which method to handle subscriber of widget.

```php
Widget::subscribe('WidgetSubscriber@handle');

class WidgetSubscriber {

	public function handle($widget)
	{
		$widget->register('image', __CLASS__ .'@image');
	}

	public function image()
	{
		return 'Your handler here';
	}
}
```

<a name="registering-widget"></a>
### Registering A Widget

By default you can register a widget in `app/widgets.php`, that file will autoload automatically.

Via Closure.

```php
// app/widgets.php

Widget::register('small', function($contents)
{
	return "<small>{$contents}</small>";
});

Widget::register('view', function($view, $data = array(), $mergeData = array()
{
	return View::make($view, $data, $mergeData)->render();
});

```

Via Class Name. 

By default will call `register` method.

```php
class MyWidget {

	public function register($contents, $attributes = array())
	{
	    $attributes = HTML::attributes($attributes);
	    
		return "<h1{$attributes}>{$contents}</h1>";
	}

} 

Widget::register('h1', 'MyWidget');
```

Via Class Name with the specified method.

```php

class TagCreator {
	
	public function create($tag, $contents, $attributes = array())
	{
		$attributes = HTML::attributes($attributes);

		return "<{$tag}{$attributes}>{$contents}</{$tag}>";
	}

} 

class HTMLWidget {

	protected $tag;

	public function __construct(TagCreator $tag)
	{
		$this->tag = $tag;
	}

	public function p($contents, $attributes = array())
	{
		return $this->tag->create('p', $contents, $attributes);
	}

	public function div($contents, $attributes = array())
	{
		return $this->tag->create('div', $contents, $attributes);
	}
} 
Widget::register('p', 'HTMLWidget@p');

Widget::register('div', 'HTMLWidget@div');

```

<a name="calling-widget"></a>
### Calling A Widget

```php
Widget::get('small', array('My Content'));

Widget::call('small', array('My Content'));

Widget::small('My Content');

Widget::p('My Content');

Widget::div('My Content');

Widget::h1('My Content');
```

On view you can call like this.

```
@small('My Content')

@view('users.show', $data, $mergeData)

@h1('Welcome!')

@p('Hello World', array('class' => 'page-header'));

@div('Lorem ipsum', array('class' => 'alert alert-warning'));
```

<a name="grouping-widget"></a>
### Grouping A Widget

It is very easy to group widget. you only need to specify the group name and specify an array of the names of the widgets that will be grouped.

```php
Widget::register('calendar', 'SidebarWidget@calendar')

Widget::register('archive', 'SidebarWidget@archive')

Widget::group('sidebar', array('calendar', 'archive'));
```

To call a group of widgets is the same as calling the widget.

```php
Widget::sidebar();
```

If you want to send parameters to the widget that is in the group, you can call it like this.

```php
Widget::sidebar(
	array('your-first-param', 'your-second-param'),
	array('first-param-for-second-widget', 'the-second')
);
```

On view you can call a group of widgets is same as calling the widget.

```
@sidebar()

@sidebar(array('first-param'), array('first-param-for-second-widget'))
```
