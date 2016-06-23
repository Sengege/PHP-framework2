(function() {
    window.App = {
        Models: {},
        Collections: {},
        Views: {},
        Router: {}
    };

    _.templateSettings = {
        interpolate: /\{\{=(.+?)\}\}/g,
        evaluate: /\{\{(.+?)\}\}/g
    };

    $.ajaxPrefilter(function(options, originalOptions, jqXHR){
        options.url = 'public/php'+options.url;
    });






// HOMEPAGE VIEW [NO MODEL REQUIRED]:
	App.Views.Home = Backbone.View.extend({
		el: '.page',
		template: _.template($('#home-page-template').html()),
		initialize: function(){
			this.render();
		},
		render: function(){
			this.$el.html(this.template());
		},
		events: {
		
		}
	});

	
// AboutUs VIEW [NO MODEL REQUIRED]:
	App.Views.About = Backbone.View.extend({
		el: '.page',
		template: _.template($('#aboutUs-page-template').html()),
		initialize: function(){
			this.render();
		},
		render: function(){
			this.$el.html(this.template());
		},
		events: {
		
		}
	});

	
	
// SIGNUP VIEW & MODEL:
/*
	App.Models.NewUser = Backbone.Model.extend({
		url: 'users/' //Maybe, (PROBABLY) 'users/create' - got to check code at home
	});
	
	App.Views.SignUp = Backbone.View.extend({
		el: '.page',
		template: _.template($('#sign-up-template').html()),
		intialize: function(){
			this.render();
		},
		render: function(){
			this.$el.html(this.template());
		},
		events: {
			'#signUpForm submit' : 'validate'
		},
		saveUser: function(){
			var newUser = new NewUser();
			//this.model = newUser;
			//Serialize form here, this.model.save(<Serialized_Form>);
			//POST->'users/'
		}
	});
	*/
	
	
	
	
	
	
    App.Models.GroupMessage = Backbone.Model.extend({
        urlRoot: '/groups'
    });

    // Message for a specific group
    App.Views.GroupMessage = Backbone.View.extend({
        el: '.page',
        // template: _.template($('#message-view-template').html()),

        initialize: function(){
			
        },

        render: function(options){
            this.model = new App.Models.GroupMessage({
                groupID: options.groupID, 
                messageID: options.messageID, //can come out later
            });

            this.model.set({
                url: this.model.urlRoot + '/' + options.groupID + '/messages',
            });

            this.model.fetch({id: options.messageID});
            console.log(this.model.attributes);
        },

        events: {

        }
    });




    App.Models.UserGroups = Backbone.Model.extend({
        urlRoot: '/groups'
    });

    //working, browse groups for user
    App.Views.BrowseGroups = Backbone.View.extend({
        el: '.page',
        model: new App.Models.UserGroups(),
        template: _.template($('#group-list-template').html()),

        initialize: function() {
            this.model.fetch();
            this.listenTo(this.model, 'change', this.render);
        },

        render: function() {
            if (this.model.errorMessage) {
                //make validate function in model
                //not logged in etc
            }
            this.$el.html(this.template({
                sGroups: this.model.get('suggested'),
                iGroups: this.model.get('groupsIn')
            }));
            return this;
        },

        events: {
            // None yet
            // ".clickMe click": 'updateUser' ..etc
        }
    });





    App.Models.Group = Backbone.Model.extend({ //TODO - may be pointless.
        urlRoot: '/groups'
    });

    App.Views.Group = Backbone.View.extend({ //TODO
        el: '.page',
        template: _.template($('#group-view-template').html()),

        initialize: function() {
            // this.listenTo(this.model, 'change', this.render);
        },

        render: function(group) {
            this.model = new App.Models.Group({id: group.id});
            this.model.fetch(); //GET->'groups/:id' ^
            this.$el.html(this.template({
                groupID: this.model.get('id'),
                userName: this.model.get('username'),
                description: this.model.get('description')
                // etc..
            }));
        },

        events: {

        }
    });






    App.Router = Backbone.Router.extend({
        routes: {
            '': 'home',
            'groups(/)': 'browseGroups',
            'groups/:id(/)': 'singleGroupView',
            'groups/:gid/messages(/)': 'allGroupMessages',
            'groups/:gid/messages/:mid(/)': 'groupMessage',
			'signup(/)' : 'signUp',
			'about(/)' : 'aboutUs'
            //etc
        },
        home: function(){
            new App.Views.Home();
        },
        browseGroups: function(){
            new App.Views.BrowseGroups().render();
        },
        singleGroupView: function(id){
            new App.Views.Group().render({id: id}); 
        },
        allGroupMessages: function(gid){
            //TODO
        },
        groupMessage: function(gid, mid){
            new App.Views.GroupMessage().render({ 
                groupID: gid, 
                messageID: mid 
            });
        },
		signUp: function(){
			new App.Views.SignUp();
		},
		aboutUs: function(){
			new App.Views.About();
		}
    });

    var router = new App.Router();
    Backbone.history.start();
})();

// var userGroups = new App.Models.UserGroups();
// var browseGroups = new App.Views.BrowseGroups({
//     model:userGroups
// });