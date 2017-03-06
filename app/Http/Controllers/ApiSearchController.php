<?php
/**
 * ApiSearchController is used for the "smart" search throughout the site.
 * it returns and array of items (with type and icon specified) so that the selectize.js plugin can render the search results properly
 **/
namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Model;
use Input;
use Response;

class Product extends Model
{
	public function reviews()
	{
	    return $this->hasMany('Review');
	}

	public function categories()
	{
		return $this->belongsToMany('Category');
	}

	public function seo()
	{
	    return $this->morphMany('Seo', 'seoble');
	}

	public function getIconAttribute()
	{
		return $this->attributes['icon'] ? url($this->attributes['icon']) : 'http://placehold.it/20x20';
	}

	// The way average rating is calculated (and stored) is by getting an average of all ratings, 
	// storing the calculated value in the rating_cache column (so that we don't have to do calculations later)
	// and incrementing the rating_count column by 1

    public function recalculateRating($rating)
    {
    	$reviews = $this->reviews()->notSpam()->approved();
	    $avgRating = $reviews->avg('rating');
		$this->rating_cache = round($avgRating,1);
		$this->rating_count = $reviews->count();
    	$this->save();
    }
}

class ApiSearchController extends BaseController {
	public function appendValue($data, $type, $element)
	{
		// operate on the item passed by reference, adding the element and type
		foreach ($data as $key => & $item) {
			$item[$element] = $type;
		}
		return $data;		
	}
		
	public function appendURL($data, $prefix)
	{
		// operate on the item passed by reference, adding the url based on slug
		foreach ($data as $key => & $item) {
			$item['url'] = url($prefix.'/'.$item['slug']);
		}
		return $data;		
	}
	public function index()
	{
		$query = e(Input::get('q',''));
		if(!$query && $query == '') return Response::json(array(), 400);
		$products = Product::where('published', true)
			->where('name','like','%'.$query.'%')
			->orderBy('name','asc')
			->take(5)
			->get(array('slug','name','icon'))->toArray();
		
		// Data normalization
		$products 	= $this->appendURL($products, 'products');
		// Add type of data to each item of each set of results
		$products = $this->appendValue($products, 'product', 'class');
		// Merge all data into one array
		$data = array_merge($products);
		return Response::json(array(
			'data'=>$data
		));
	}
}