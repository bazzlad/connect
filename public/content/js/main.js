// loader
var _loader = {
	skip: false,
	show: function(msg) {
		if (_loader.skip) {
			return;
		}
		if (!msg) {
			msg = `
				<span class="text-center display-block"><i class="la la-spin la-spinner"></i> Thinking&hellip;</span>
			`;
		}
		_loader.obj.push(bootbox.dialog({
			message: msg,
			closeButton: false,
			animate: false
		}));
	},
	hide: function() {
		if (_loader.skip) {
			_loader.skip = false;
			return;
		}
		if (_loader.obj.length) {
			_loader.obj[0].modal('hide');
			_loader.obj.shift();
		}
	},
	obj: []
};

// auto show loader for axios calls
axios.interceptors.request.use(function(config) {
	// Do something before request is sent
	_loader.show();
	return config;
}, function(error) {
	// Do something with request error
	console.log('Axios: Error');
	window.setTimeout(function() {
		_loader.hide();
		bootbox.alert('There has been a connectivity error, please try again');
	}, 500);
	return Promise.reject(error);
});

axios.interceptors.response.use(function(response) {
  // Do something with response data
	_loader.hide();
	return response;
}, function(error) {
	// Do something with response error
	console.log('Axios: Error fetching the data');
	window.setTimeout(function() {
		_loader.hide();
		bootbox.alert('There has been a connectivity error, please try again');
	}, 500);
	return Promise.reject(error);
});
