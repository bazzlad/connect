/*
	This runs the main intercom view
	Checks for and displays messages
*/

var app = new Vue({
	el: '#app',
	data: {
		message: '',
		prio: 0,
		isViewMode: false,
	},
	computed: {
	},
	mounted: function() {
		this.init();
	},
	methods: {
		init() {
		},
		sendMessage() {
			var message = this.buildMessage();

			// get messages
			axios.post('/api/messages', message)
			.then(function(r) {
				if (r.data.result === "error") {
					bootbox.alert(r.data.data);
					return;
				}
				bootbox.alert("Message Sent!");
				app.message = '';
				app.prio = 0;
			})
			.catch(function (error) {
			})
			// always run
			.then(function() {
			});
		},
		buildMessage() {
			// could have validation
			var obj = {
				type: 'add',
				prio: app.prio,
				message: app.message,
				active: 1
			};

			return obj;
		},
		getMessages() {
			if (!this.isViewMode) {
				return;
			}
			// message-list
			_loader.skip = true;
			axios.post('/api/messages', {
				type: 'get',
				all: true
			})
			.then(function(r) {
				if (r.data.result === "success") {
					app.showMessages(r.data.data);
				} else {
					console.log(r.data);
				}
			})
			.catch(function(e) {
				console.log(e);
			})
			.then(function(r) {
				// let's recheck
				window.setTimeout(function() {
					app.getMessages();
				}, 2000);
			});
		},
		toggleView() {
			if (this.isViewMode) {
				this.isViewMode = false;
			} else {
				this.isViewMode = true;
				this.getMessages();
			}
		},
		showMessages(messages) {
			var content = '<br />';
			if (!messages.length) {
				content += `
					<p class="bold text-center">No Messages Found</p>
				`;
			} else {
				content += `
					<table class="table">
						<thead>
							<tr>
								<th>ID</th>
								<th>Message</th>
								<th>Priority</th>
								<th>Seen</th>
							</tr>
						</thead>
						<tbody>
				`;
				for (var i=0; i<messages.length; i++) {
					var message = messages[i];
					var className = "message-seen";
					if (message.active) {
						className = "message-unseen";
					}

					content += `
						<tr class="${className}">
							<td>${message.id}</td>
							<td>${message.message}</td>
							<td>${message.prio ? '<span class="bold text-danger">Emergency</span>' : 'Standard'}</td>
							<td>${message.active ? 'No' : 'Yes'}</td>
						</tr>
					`;
				}
				content += `
						</tbody>
					</table>
				`;
			}
			document.getElementById('message-list').innerHTML = content;
		}
	}
});
