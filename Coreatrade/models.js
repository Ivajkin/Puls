/*globals require: false, exports: false */

var crypto = require('crypto'),
	LoginToken,
	ServiceType,
	UserService,
	Resource,
	Facility,
	City,
	Region,
	UserRequest,
	Locations,
	Admins,
	Users,
	FacilityRequests;

var valid = {
	date: function(string) {
	  return (/\d+\s*.\s*\d+.\s*\d+/).test(string);
	},
	time: function(string) {
	  return (/\d+\s*:\s*\d+/).test(string);
	},
	wdatetime: function(string) {
	  return (/\d+\.\d+\.\d+@\d+\s*:\s*\d+/).test(string);
	},
	positive: function(number) {
		return (number > 0);
	}/*,
	not_negative: function(number) {
		return (number >= 0);
	},
	integer: function(number) {
		return (Math.round(number) === number);
	},
	id: function(id) {
		return (valid.not_negative(id) && valid.integer(id));
	}*/
};

function defineModels(mongoose, callback) {
	var Schema = mongoose.Schema, ObjectId = Schema.ObjectId;


	/* Additional */
	var WideIntervalSchema = new Schema({
		"wstart": { type: String, validate: [valid.wdatetime, 'invalid date/time format'] },
		"wfinish": { type: String, validate: [valid.wdatetime, 'invalid date/time format'] }
	});
	var DayIntervalSchema = new Schema({
		"start": { type: String, validate: [valid.time, 'invalid time format'] },
		"finish": { type: String, validate: [valid.time, 'invalid time format'] },
		"service_type_ids": [Number],
		"date": { type: String, validate: [valid.date, 'invalid date format'] }
	});
	
	/**
	 * Model: ServiceType
	 */
	ServiceType = new Schema({
		"id" : Number,
		"name" : String,
		"icon" : String,
		"description" : String,
		"default_time_to_complete_service": Number,
		"type": String,
		"facility_type": String
	});
	/**
	 * Model: UserService
	 */
	UserService = new Schema({
		"id": Number,
		"resource_id": Number,
		"facility_id": Number,
		"cost": Number,
		"to_complete_service_time": { type: Number, validate: [valid.positive, 'to_complete_service_time is negative'] },
		"location_id": Number,
		"available_time": [DayIntervalSchema]
	});
	/**
	 * Model: Resource
	 */
	Resource = new Schema({
		"id": Number,
		"name": String,
		"description": String,
		"disabled": [WideIntervalSchema],
		"service_type_ids": [Number],
		"prolog_name": String
	});
	/**
	 * Model: Facility
	 */
	Facility = new Schema({
		"id": Number,
		"name": String,
		"city_id": Number,
		"description": String,
		"homepage_url": String,
		"service_type_ids": [Number],
		"prolog_name": String,
		"DEBUG": Boolean
	});
	/**
	 * Model: City
	 */
	City = new Schema({
		"id" : Number,
		"name" : String,
		"latitude" : Number,
		"longitude" : Number,
		"timezone" : String,
		"region_id": Number
	});
	/**
	 * Model: Region
	 */
	Region = new Schema({
		"id": Number,
		"name": String
	});
	
	UserRequest = new Schema({
		"phone_number": String,
		"registration_id": String,
		"resource_id": Number,
		"work_start_time_minutes": Number,
		"human_readable_date": String,
		"human_readable_time": String,
		"_cached_resource_name": String,
		"_cached_facility_id": Number,
		"_cached_service_type_id": Number,
		"_date_time_registered": String,
		"prolog_name": String
	});

	Locations = new Schema({
		"id": Number,
		"name": String,
		"facility_id": Number
	});
	
	Admins = new Schema({
		"description": String,
		"email": String,
		"facility_id": Number,
		"id": Number,
		"login": String,
		"name": String,
		"password_md5": String,
		"phone_number": String 
	});
	
	Users = new Schema({
		"id": Number,
		"phone_number": String,
		"password": String,
		"name": String,
		"last_remind_date": String
	});
	
	FacilityRequests = new Schema({
		"id": Number,
		"facility_name": String,
		"contact_name": String,
		"contact_phone_number": String,
		"request_date": String
	});
				
				
	mongoose.model('service_types', ServiceType);
	mongoose.model('user_services', UserService);
	mongoose.model('resources', Resource);
	mongoose.model('facilities', Facility);
	mongoose.model('cities', City);
	mongoose.model('regions', Region);
	mongoose.model('user_requests', UserRequest);
	mongoose.model('locations', Locations);
	mongoose.model('admins', Admins);
	mongoose.model('users', Users);
	mongoose.model('facility_requests', FacilityRequests);
	callback();
}

exports.defineModels = defineModels;
