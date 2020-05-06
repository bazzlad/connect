

var app = new Vue({
	el: '#app',
	data: {
	},
	computed: {
	},
	mounted: function() {
		this.init();
	},
	methods: {
		init() {
			window.setTimeout(function() {
				app.displayNotification();
			}, 3000);
		},
		displayNotification() {
			var content = `
				<button type="button" 
					class="btn btn-light btn-lg"
					onclick="app.showNotification()"
				>
					View Message
				</button>
			`;
			// write content
			var ele = document.getElementById('notification-block');
			ele.innerHTML = content;

			// update screen
			var ele = document.getElementsByTagName('body')[0];
			ele.classList.add('message');
		},
		showNotification() {
			var ele = document.getElementById('message-container');
			ele.classList.remove('hide');
		},
		messageConfirm() {
			var ele = document.getElementById('message-container');
			ele.classList.add('hide');

			ele = document.getElementsByTagName('body')[0];
			ele.classList.remove('message');

			ele = document.getElementById('notification-block');
			ele.innerHTML = '';
		}
	}
});
