<?php
	namespace App\Console\Commands;
	use Illuminate\Console\Command;
	use App\Models\User;
	use Mail;
	class SendMail extends Command
	{
		/**
			* The name and signature of the console command.
			*
			* @var string
		*/
		protected $signature = 'mail:update';
		/**
			* The console command description.
			*
			* @var string
		*/
		protected $description = 'Send an email to all the users';
		/**
			* Create a new command instance.
			*
			* @return void
		*/
		public function __construct()
		{
			parent::__construct();
		}
		/**
			* Execute the console command.
			*
			* @return mixed
		*/
		public function handle()
		{
			$user = User::all();
			
				Mail::raw("This is automatically generated Hourly Update", function($message) 
				{
					$message->from('saquib.gt@gmail.com');
					$message->to('kaushalkishor5896@gmail.com')->subject('Hourly Update');
				});
			
			$this->info('Hourly Update has been send successfully');
		}
	}	