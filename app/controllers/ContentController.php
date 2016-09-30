<?php

class ContentController extends BaseController {

	protected $layout = 'layouts.site';

	protected $article;
	protected $tag;

	public function __construct(Fideloper\Storage\Article\ArticleInterface $article, Fideloper\Storage\Tag\TagInterface $tag)
	{
		$this->article = $article;
		$this->tag = $tag;
	}

	/**
	* Display listing of latest articles
	*
	* @todo handle "no articles" situation?
	*/
	public function index()
	{
		$page = Input::get('page', 1);
		$perPage = 10;

		$pagiData = $this->article->getPaginated($page, $perPage);
		$articles = Paginator::make($pagiData->items, $pagiData->totalItems, $pagiData->limit);

		$tags = $this->tag->getPopular();

		$this->layout->content = View::make('content.home')->with('articles', $articles)->with('tags', $tags);

		$response = Response::make($this->layout, 200);
		$response->setCache([
			'last_modified' => DateTime::createFromFormat('Y-m-d G:i:s', $articles[0]->updated_at),
			'max_age' => 86400, // One day
			'public' => true,
		]);

		return $response;
	}

	/**
	* Display specific article
	*
	* @todo Is there a better way to rid of Markdown syntax but not have HTML
	*       for meta tag description?
	*/
	public function article($slug)
	{
		$article = $this->article->getBySlug($slug);

		if( !$article )
		{
			App::abort(404);
		}

		$recents = $this->article->getRecent();

		// Head data
		$tags = [];
		foreach( $article->tags as $tag )
		{
			$tags[] = $tag->name;
		}

		$head = App::make('headdata');
		$head->add('title', $article->title);
		$head->add('keywords', implode(',', $tags));
		$head->add('description', trim(strip_tags(\Michelf\MarkdownExtra::defaultTransform($article->excerpt))));

		// Output Article
		$this->layout->content = View::make('content.article', [
			'article' => $article,
			'recents' => $recents,
			'context' => array(
				'where' => 'frontend'
			)
		]);

		$response = Response::make($this->layout, 200);
		$response->setCache([
			'last_modified' => DateTime::createFromFormat('Y-m-d G:i:s', $article->updated_at),
			'max_age' => 86400, // One day
			'public' => true,
		]);

		return $response;
	}

	/**
	* Display articles under a tag
	*/
	public function tag($tag)
	{
		$page = Input::get('page', 1);
		$perPage = 10;

		$pagiData = $this->article->getByTag($tag, $page, $perPage);
		$articles = Paginator::make($pagiData->items, $pagiData->totalItems, $pagiData->limit);

		if( count($articles) === 0 )
		{
			App::abort(404);
		}

		$tags = $this->tag->getPopular();

		// Head data
		$head = App::make('headdata');
		$head->add('title', $tag . ' | Fideloper');

		// Output Articles
		$this->layout->content = View::make('content.tags', [
			'articles' => $articles,
			'tag' => $tag,
			'tags' => $tags,
		]);

		$response = Response::make($this->layout, 200);
		$response->setCache([
			'last_modified' => DateTime::createFromFormat('Y-m-d G:i:s', $articles[0]->updated_at),
			'max_age' => 86400, // One day
			'public' => true,
		]);

		return $response;
	}

	/**
	* Display articles in a time range
	*/
	public function archive($date)
	{
		return $date;
	}

	public function feed($kind=null)
	{
		$feed = new Suin\RSSWriter\Feed();
		$channel = new Suin\RSSWriter\Channel();

		$channel
		    ->title( "Fideloper" )
		    ->description( "Lead dev @digitalsurgeons. I do LAMP, Laravel, Nodejs, Python, and lots of server stuff." )
		    ->url( 'http://fideloper.com' )
		    ->appendTo( $feed );

		$articles = $this->article->getRecent(30);

		foreach( $articles as $article )
		{
			$item = new Suin\RSSWriter\Item();

			$item
			    ->title( $article->title )
			    ->url( 'http://fideloper.com/'.$article->url_title )
			    ->appendTo( $channel );

			if($kind === 'full')
			{
				$item->description( \Michelf\MarkdownExtra::defaultTransform($article->content) );
			} else {
				$item->description( $article->excerpt );
			}
		}

		return Response::make($feed, 200, ['Content-Type' => 'application/xml']);

	}

}
