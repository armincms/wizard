
## This package is archived. please use the [Nova Wizard](https://github.com/zareismail/nova-wizard) package instead of it.



# wizard
The wizard form for the laravel nova


##### Table of Contents   

* [Introduction](#introduction)      
* [Installation](#installation)      
* [Resource Configurations](#resource-configurations)    
* [About Implementation](#about-implementation)  


## Introduction
The `wizard` gives you the ability to creating or updating a resource step by step via validating each step.

## Installation

To get started with Bios run the below command:

```    
    composer require armincms/wizard
```

## Resource Configurations

If you want to use wizard form for editing a resource; must add the following method into the resource:

``` 
    /**
     * Get meta information about this resource for client side comsumption.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function additionalInformation(Request $request)
    {
        return ['wizard' => true];
    }
```

Then for defining the steps can use `Armincms\Wizard\Step` like the following example:


```

use Armincms\Wizard\Step;


calss MyResource extends Resource
{  
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
    	return [
    		// some fields

    		Step::make('Step One', [
    			// step one fields
    		]),

    		Step::make('Step Two', [
    			// step two fields
    		]),

    		Step::make('Step Three', [
    			// step three fields
    		]),
    	];
    }
}
 

```

Now; your resource automaticaly display wizard form to you.

## About Implementation

Under the hood; `Armincms \ Wizard \ Step` is a panel. so when going to the next step; all fields in that panel and previous steps should pass the validation rules. If validation fails;  the first panel that contains an unvalidated attribute will be displayed.

If you want to validation some fields on each step, you should define those fields without an `Armincms\Wizard\Step`. like the following:


```

use Armincms\Wizard\Step;


calss MyResource extends Resource
{  
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
    	return [

    		// bellow fields will be validated per each step

    		Text::make("Text"),

    		Text::make("Test"),

    		// and some other fields


    		// bellow fields will be validated on the active step

    		Step::make('Step One', [
    			// step one fields
    		]),

    		Step::make('Step Two', [
    			// step two fields
    		]),

    		Step::make('Step Three', [
    			// step three fields
    		]), 
    	];
    }
} 

```


![wizard 1](/w1.png)
![wizard 2](/w2.png)
![wizard 3](/w3.png)
