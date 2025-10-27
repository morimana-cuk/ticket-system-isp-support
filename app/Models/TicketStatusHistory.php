<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatusHistory extends Model
{
	protected $table = 'ticket_status_histories';
	protected $guarded = [];

	public function ticket()
	{
		return $this->belongsTo(ticket_problem::class, 'ticket_number', 'ticket_number');
	}

	public function user()
	{
		return $this->belongsTo(Account::class, 'changed_by');
	}
}
