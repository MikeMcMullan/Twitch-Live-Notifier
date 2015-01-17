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

		$channelQuotes = QuoteChannels::with('quotes')
			->where('name', '=', $channel)
			->orWhere('key', '=', $channel)
			->first();

		if ($channelQuotes)
		{
			$channel = $channelQuotes->display_name;
			$quotes = $channelQuotes->quotes;
			$randomQuote = array_rand($quotes->toArray());

			$quote = $quotes[$randomQuote];

			return sprintf('Quote #%d "%s" - %s %s ', $randomQuote+1, $quote->text, $channel, $quote->created_at->format('F Y'));
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
			'channel'  => 'required|alpha_num|min:32|max:32',
			'text'     => 'required|min:5|max:255'
		];

		$validator = Validator::make($data, $rules);

		if ($validator->fails())
		{
			return $validator->messages()->first();
		}

		$channel = QuoteChannels::where('key', '=', $data['channel'])->first();

		if ( ! $channel)
		{
			return 'Invalid channel.';
		}

		$quote = new Quote([
			'text' => $data['text']
		]);

		if ($channel->quotes()->save($quote))
		{
			return sprintf('Quote added: "%s"', $data['text']);
		}

		return 'There was an error creating the quote, please try again later.';
	}
}
