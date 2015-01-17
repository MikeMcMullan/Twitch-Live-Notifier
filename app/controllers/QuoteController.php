<?php

class QuoteController extends \BaseController {

	/**
	 * Display a random quote for a channel.
	 *
	 * @return Response
	 */
	public function index()
	{
		$channel = Input::get('channel');
		$quote = Quote::where('channel', '=', $channel)
			->orderBy(DB::raw('RAND()'))
			->limit(1)
			->get();

		if ( ! $quote->isEmpty())
		{
			$quote = $quote->first();
			return sprintf('Quote #%d "%s" - %s %s ', $quote->id, $quote->text, $quote->channel, $quote->created_at->format('F Y'));
		}

		return 'No quotes available.';
	}


	/**
	 * Create a new quote.
	 *
	 * @return Response
	 */
	public function create()
	{
		$data = Input::only(['channel', 'text']);
		$rules = [
			'channel'  => 'required|alpha_dash',
			'text'     => 'min:5|max:255'
		];

		$validator = Validator::make($data, $rules);

		if ($validator->fails())
		{
			return $validator->messages()->first();
		}

		$quote = new Quote([
			'text' 		=> $data['text'],
			'channel'	=> $data['channel']
		]);

		if ($quote->save())
		{
			return sprintf('Quote added: "%s"', $data['text']);
		}

		return 'There was an error creating the quote, please try again later.';
	}
}
