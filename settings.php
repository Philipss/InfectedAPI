<?php
class Settings {
	/* Metadata */
	const name = 'Infected';
	const description = 'Infected er et av Akershus største datatreff (LAN-party), og holder til i kulturhuset i Asker kommune.';
	const keywords = 'infected, lan, party, asker, kulturhus, ungdom, gaming';
	
	/* Database */
	const db_host = 'localhost';
	const db_name_infected = 'test_infected_no';
	const db_name_infected_compo = 'test_infected_no_compo';
	const db_name_infected_crew = 'test_infected_no_crew';
	const db_name_infected_info = 'test_infected_no_info';
	const db_name_infected_main = 'test_infected_no_main';
	const db_name_infected_tickets = 'test_infected_no_tickets';
	
	// Infected
	const db_table_infected_emergencycontacts = 'emergencycontacts';
	const db_table_infected_events = 'events';
	const db_table_infected_locations = 'locations';
	const db_table_infected_passwordresetcodes = 'passwordresetcodes';
	const db_table_infected_permissions = 'permissions';
	const db_table_infected_postalcodes = 'postalcodes';
	const db_table_infected_registrationcodes = 'registrationcodes';
	const db_table_infected_useroptions = 'useroptions';
	const db_table_infected_userpermissions = 'userpermissions';
	const db_table_infected_users = 'users';
	
	// InfectedCompo
	const db_table_infected_compo_chatmessages =  'chatmessages';
	const db_table_infected_compo_chats =  'chats';
	const db_table_infected_compo_clans = 'clans';
	const db_table_infected_compo_compos = 'compos';
	const db_table_infected_compo_invites = 'invites';
	const db_table_infected_compo_memberof = 'memberof';
	const db_table_infected_compo_memberofchat =  'memberofchat';
	const db_table_infected_compo_participantof = 'participantof';
	const db_table_infected_compo_voteoptions = 'voteOptions';
	const db_table_infected_compo_participantOfMatch = 'participantofmatch';
	const db_table_infected_compo_matches = 'matches';
	const db_table_infected_compo_readyusers = 'readyUsers';
	const db_table_infected_compo_votes =  'votes';
	
	// InfectedCrew
	const db_table_infected_crew_applicationqueue = 'applicationqueue';
	const db_table_infected_crew_applications = 'applications';
	const db_table_infected_crew_avatars = 'avatars';
	const db_table_infected_crew_groups = 'groups';
	const db_table_infected_crew_memberof = 'memberof';
	const db_table_infected_crew_pages = 'pages';
	const db_table_infected_crew_teams = 'teams';
	
	// InfectedInfo
	const db_table_infected_info_slides = 'slides';
	
	// InfectedMain
	const db_table_infected_main_agenda = 'agenda';
	const db_table_infected_main_gameapplications = 'gameapplications';
	const db_table_infected_main_games = 'games';
	const db_table_infected_main_pages = 'pages';
	
	// InfectedTickets
	const db_table_infected_tickets_checkinstate = 'checkinstates';
	const db_table_infected_tickets_entrances = 'entrances';
	const db_table_infected_tickets_paymentlog = 'payments';
	const db_table_infected_tickets_rows = 'rows';
	const db_table_infected_tickets_seatmaps = 'seatmaps';
	const db_table_infected_tickets_seats = 'seats';
	const db_table_infected_tickets_storesessions = 'storesessions';
	const db_table_infected_tickets_tickets = 'tickets';
	const db_table_infected_tickets_tickettransfers = 'tickettransfers';
	const db_table_infected_tickets_tickettypes = 'tickettypes';

	/* Configuration */
	
	/* InfectedAPI */
	// Full path to the API location.
	const api_path = '/home/test.infected.no/public_html/api/';
	
	// Email information.
	const emailName = 'Infected';
	const email = 'no-reply@infected.no';
	
	// Tells where QR images should be stored.
	const qr_path = 'content/qrcache/';
	const avatar_path = 'content/avatars/';
	
	/* Compo */
	// Match participant of state.
	const compo_match_participant_type_clan = 0;
	const compo_match_participant_type_match_winner = 1;
	const compo_match_participant_type_match_looser = 2;
	
	/* Crew */
	// Avatar sizes.
	const avatar_thumb_w = 150;
	const avatar_thumb_h = 133;

	const avatar_sd_w = 800;
	const avatar_sd_h = 600;

	const avatar_hd_w = 1200;
	const avatar_hd_h = 900;

	const thumbnail_compression_rate = 100;
	const sd_compression_rate = 100;
	const hd_compression_rate = 100;

	const avatar_minimum_width = 1200;
	const avatar_minimum_height = 900;

	/* Tickets */
	// How long time before the tickets event should allow it to be refunded?
	const refundBeforeEventTime = 1209600; // 14 days (14 * 24 * 60 * 60)
	
	// How long a time should the ticket be stored on your account before payment is successful?
	const storeSessionTime = 3600; // 1 Hour (60 * 60)
	
	// How long time after ticket is transfered should we allow the former owner to revert the transaction?
	const ticketTransferTime = 86400; // 1 day (24 * 60 * 60)
}
?>
