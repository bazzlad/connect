/*
	This runs the main intercom view
	Checks for and displays messages
*/

var app = new Vue({
	el: '#app',
	data: {
		// in ms
		checkFrequency: 2000,
		// are we displaying a message?
		busy: false,
		currentMessage: null,
		alarm: null
	},
	computed: {
	},
	mounted: function() {
		this.init();
	},
	methods: {
		init() {
			// turn off default interceptors
			axios.interceptors.request.handlers = [];
			axios.interceptors.response.handlers = [];

			this.statusUpdate();
			this.getMessages();
		},
		getMessages() {
			// if we have a message, stop
			if (this.busy) {
				return;
			}
			// get messages
			axios.post('/api/messages', {
				type: 'get'
			})
			.then(function(r) {
				var result = r.data;
				if (result.result !== "success") {
					app.statusUpdate(true);
					return;
				}
				app.statusUpdate();
				app.processMessages(result.data);
			})
			.catch(function (error) {
				console.log(error);
				app.statusUpdate(true);
			})
			// always run
			.then(function() {
				window.setTimeout(function() {
					app.getMessages();
				}, app.checkFrequency);
			});
		},
		processMessages(messages) {
			if (!messages.length) { return; }
			var message = messages[0];

			this.busy = true;
			this.currentMessage = message;
			// display message
			this.displayMessage(message);
		},
		displayMessage(message) {
			var content = `
				<button type="button" 
					class="btn btn-light btn-lg"
					onclick="app.showMessage()"
				>
					View Message
				</button>
			`;
			// write content
			var ele = document.getElementById('notification-block');
			ele.innerHTML = content;

			// update screen
			var className = 'message';
			// if prio play alarm
			if (message.prio) {
				className = 'prioMessage';
				this.alarm = new Audio('/content/audio/alarm.mp3');
				this.alarm.loop = true;
				this.alarm.play();
			}
			var ele = document.getElementsByTagName('body')[0];
			ele.classList.add(className);
		},
		showMessage() {
			var ele = document.getElementById('message-container');
			ele.classList.remove('hide');
			
			if (this.currentMessage.message) {
				var messageText = `
					<h4>${this.currentMessage.message}</h4>
				`;

				// append time
				var timestamp = new Date(this.currentMessage.timestamp);
				var cleanTS = timestamp.getHours() + ':' + timestamp.getMinutes();

				messageText += `
					<p>Sent at ${cleanTS}</p>
				`;

				document.getElementById('message-text').innerHTML = messageText;
			} else {
				document.getElementById('message-text').innerHTML = '';
			}

			debugger;

			// turn off alarm
			if (app.alarm) {
				this.alarm.pause();
				this.alarm = null;
			}
		},
		messageConfirm() {
			var ele = document.getElementById('message-container');
			ele.classList.add('hide');

			ele = document.getElementsByTagName('body')[0];
			ele.classList.remove('message');
			ele.classList.remove('prioMessage');

			ele = document.getElementById('notification-block');
			ele.innerHTML = '';

			this.markMessageSeen();
		},
		markMessageSeen() {
			if (!this.currentMessage) { return };
			var messageId = this.currentMessage.id;

			axios.post('/api/messages', {
				type: 'seen',
				id: messageId
			})
			.then(function(r) {
				// not sure anything is needed
				var result = r.data;
				if (result.result !== "success") {
					app.statusUpdate(true);
					console.log(r.data);
					return;
				}
			})
			.catch(function (error) {
				console.log(error);
				app.statusUpdate(true);
			})
			// always run
			.then(function() {
				window.setTimeout(function() {
					app.busy = false;
					app.currentMessage = null;
					app.getMessages();
				}, app.checkFrequency);
			});
		},
		statusUpdate(offline) {
			var className = "online";
			if (offline) {
				className = "offline";
			}
			// update screen
			var ele = document.getElementsByTagName('body')[0];
			ele.classList.remove('online');
			ele.classList.remove('offline');
			ele.classList.add(className);
		}
	}
});
