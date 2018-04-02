<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Devil Time | by Pharosic</title>

    <link rel="shortcut icon" type="image/png" href="">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.0/semantic.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/main.css'); ?>">

    <link rel="stylesheet" href="<?php echo base_url('assets/css/offline-js/0.7.19/themes/offline-language-english.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/offline-js/0.7.19/themes/offline-theme-slide.min.css'); ?>">

    <link rel="stylesheet" href="https://unpkg.com/vue-loading-overlay@latest/dist/vue-loading.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.3.0/css/perfect-scrollbar.min.css" />
  </head>
  <body>
    <div id="app">
      <div class="ui two column doubling stackable grid">
        <div class="row" style="padding-bottom: 0px">

          <div class="side content five wide column">
            <div class="header">
              <div class="logo">
                <img class="ui mini image" src="http://ct.pharosic.com/assets/favicon-9ib6rx9h.png" alt="Devil Time">
              </div>
              <div class="links">
                <div class="ui transparent icon input">
                  <input class="search input" type="text" placeholder="Search...">
                  <i class="search icon"></i>
                </div>
              </div>
            </div>

            <div class="identity select">
              <div class="ui circular icon button" @click="avatarChange(0)"><i class="chevron left icon"></i></div>
              <div class="loader" v-show="prevLoading">
                <clip-loader :loading="prevLoading" color="#1678C2" size="50px" margin="10px" radius="100%" style="padding: 30px 0px;"></clip-loader>
                Loading preview...
              </div>
              <transition name="fadeIn">
                <div class="preview" v-show=" ! prevLoading">
                  <img class="ui small image" :src="avatarSelected.filename" :alt="avatarSelected.label">
                  <div class="identity-name">
                    <span>{{ identityMood }}</span>
                    <span>{{ identityLabel }}</span>
                  </div>
                </div>
              </transition>
              <div class="ui circular icon button" @click="avatarChange(1)"><i class="chevron right icon"></i></div>
            </div>

            <div class="mood select">
              <label>I'm feeling...</label>
              <beat-loader :loading="moodLoading" color="#1678C2" size="10px" margin="2px" radius="100%" style="margin-left: 20px; margin-top: 5px;"></beat-loader>
              <select class="ui search selection tiny compact dropdown" @change="moodChange" v-if="! moodLoading" v-model="moodSelected">
                <option :value="mood.value" v-for="mood in moods">{{ mood.label }}</option>
              </select>
            </div>

            <div class="post create">
              <button class="ui primary button" @click="showPostModal" v-model="user.post_count">Create New Confession</button>
              <br><small v-html="devilToken" style="color: #FFFFFF"></small>
            </div>

            <div class="footer">
              <div class="copyright">
                The above avatar is made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">Flaticon</a>.
              </div>
              <div class="copyright">
                All rights reserved © 2018 Pharosic. &nbsp;&nbsp; About  | Privacy | Terms | Feedback
              </div>
            </div>
          </div>

          <div class="main content eleven wide column">
            <div class="ui two column doubling grid">
              <div class="row">
                <div class="fourteen wide column">
                  <div class="ui secondary pointing menu">
                    <a class="active item">Latest</a>
                    <a class="item">Hot</a>
                    <a class="item">Newest</a>

                    <div class="right menu">
                      <div class="item">
                        <span>Login to comment</span>
                        <a href="#" @click="openFbLoginDialog"><i class="facebook big icon"></i></a>
                        <a href="#"><img class="ui mini image" src="http://ct.pharosic.com/assets/favicon-9ib6rx9h.png"></a>
                      </div>
                    </div>
                  </div>
                  <div class="loader" v-show="postLoading">
                    <beat-loader :loading="postLoading" color="#1678C2" size="15px" margin="2px" radius="100%" style="padding: 15px 0px;"></beat-loader>
                    Loading content...
                  </div>
                  <div class="loader" v-show="postNoContent">
                    No content.
                  </div>
                  <div class="ui container" v-show=" ! postLoading" v-infinite-scroll="loadMore" infinite-scroll-distance="10">
                    <article class="ui fluid card" v-for="post in posts">
                      <div class="article content">
                        <span class="meta">
                          <img class="ui mini spaced image" :src="post.avatar">
                          <span class="author">{{ post.author }}</span>
                          <span class="post-id">
                            <a :href="post.postID">#{{ post.id }}</a>
                          </span>
                          <span class="in" v-if="post.source !== ''">from</span>
                          <span class="category" v-if="post.source !== ''">
                            <a href="#">{{ post.source }}</a>
                          </span>
                        </span>
                        <span class="right floated meta">
                          <time :data-tooltip="post.createdOn">{{ timeAgo(post.createdOn) }}</time>
                        </span>
                        <div class="description">
                          <i>
                            <span class="post-id" v-show="false">
                              <a :href="post.postID">#{{ post.id }}</a>
                            </span>
                            <span class="quote" v-show="false">in reply to</span>
                            <span class="quote-id" v-show="false">
                              <a :href="post.quoteID">#</a>
                            </span>
                          </i>
                          <div class="text">{{ post.description }}</div>
                        </div>
                      </div>
                      <div class="action content">
                        <a class="like">
                          <i class="heart like active icon"></i>
                            {{ post.likes }} likes
                        </a>
                        <span class="right floated">
                          <a class="comment">
                            <i class="comment icon"></i>
                            {{ post.comments }} comments
                          </a>
                          <a class="share">
                            <i class="share icon"></i>
                            {{ post.shares }} shares
                          </a>
                        </span>
                      </div>

                      <div class="extra content">
                        <div class="ui comments">
                          <a>View all comments</a>
                          <div class="comment">
                            <a class="avatar">
                              <img src="https://image.flaticon.com/icons/svg/141/141747.svg">
                            </a>
                            <div class="content">
                              <a class="author">Matt</a>
                              <div class="metadata">
                                <span class="date">
                                  <time datetime="2018-03-23T09:24:17Z">March 23, 2018</time>
                                </span>
                              </div>
                              <div class="text">
                                How artistic!
                              </div>
                              <div class="actions">
                                <a class="like">
                                  <i class="heart like icon"></i>
                                   17 likes
                                </a>
                                <a class="reply">Reply</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="extra content">
                        <div class="ui large transparent right icon fluid input">
                          <input type="text" placeholder="Add Comment...">
                          <i class="reply icon"></i>
                        </div>
                      </div>
                    </article>
                    <div class="loader" v-show="loadingMore">
                      <beat-loader :loading="loadingMore" color="#1678C2" size="15px" margin="2px" radius="100%" style="padding: 15px 0px;"></beat-loader>
                      Loading...
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="ui basic small modal" v-show="postModal">
        <div class="page-loader main" v-show="modalLoading" style="display: flex; flex-direction: column; justify-content: center; align-items: center; color: #FFFFFF">
          <clip-loader :loading="modalLoading" color="#1678C2" size="50px" margin="10px" radius="100%" style="padding: 30px 0px"></clip-loader>
          Loading editor...
        </div>
        <form @submit.prevent="postSubmit" v-if=" ! modalLoading">
          <div class="ui header" style="color: #FFFFFF; font-size: 15px; text-align: justify; padding: 10px 20px" v-show="false">
            Choose at least one category
              <span class="" style="float: right; cursor: pointer" @click="closePostModal"><i class="close icon"></i></span>
          </div>
          <div class="ui form" style="padding: 0px 20px" v-show="false">
            <div class="grouped field" style="background: #FFFFFF; border-radius: 4px; padding: 10px 30px">
              <div class="inline field" style="display: flex; flex-direction: row; justify-content: space-around; flex-wrap: wrap;">
                <div class="ui toggle checkbox" style="margin: 10px" v-for="checkbox in categories">
                  <input type="checkbox" :id="checkbox.value" :value="checkbox.value" v-model="category">
                  <label style="color: #1E70BF">{{ checkbox.label }}</label>
                </div>
              </div>
            </div>
          </div>
          <div class="ui header" style="color: #FFFFFF; font-size: 15px; text-align: justify; padding: 10px 20px; margin-top: 20px">
            Share your story with us.
          </div>
          <div class="ui form" style="padding: 0px 20px">
            <div class="field">
              <textarea style="font-size: 15px; background-color: #FAFBFC" rows="5" v-model="description" @focus="clearNotice();" @blur="showNotice();">{{ description }}</textarea>
            </div>
          </div>
          <div class="actions" style="display: flex; justify-content: space-between; padding: 15px">
            <div class="ui form" style="display: inline-block; margin-left: 5px">
              <div class="two wide field">
                <select class="ui search dropdown" v-model="source" style="width: 200px; font-size: 15px">
                  <option value="">Select University</option>
                  <option v-for="source in sources" :value="source.value">{{ source.label }}</option>
                </select>
              </div>
            </div>
            <button type="submit" class="ui primary ok button">
              <i class="paper plane icon"></i>
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.0/semantic.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/offline-js/0.7.19/offline.min.js"></script>
    <script src="https://cdn.bootcss.com/timeago.js/3.0.2/timeago.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fingerprintjs2/1.6.1/fingerprint2.min.js"></script>

    <!--- <script src="https://cdn.jsdelivr.net/npm/vue"></script> --->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue-resource@1.5.0"></script>
    <script src="http://greyby.github.io/vue-spinner/dist/vue-spinner.js"></script>
    <script src="https://unpkg.com/vue-infinite-scroll"></script>
    <script src="https://cdn.auth0.com/js/auth0/9.3.1/auth0.min.js"></script>

    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '403552113156005',
          cookie     : true,
          xfbml      : true,
          version    : 'v2.8'
        });

        FB.AppEvents.logPageView();
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "https://connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));

      Vue.http.options.emulateJSON = true;

      var BeatLoader = VueSpinner.BeatLoader;
      var ClipLoader = VueSpinner.ClipLoader;

      var textareaNotice = "Have an interesting story to share or just need to get something off your chest? Tell us your story here! No one will know it was you. Please be reminded to be socially responsible. No racial, religious or other forms of sensitive material. These entries will be rejected (and make us sad).";

      var app = new Vue({
        el: "#app",
        data: {
          //----- Global -----//
            user: [],
            devilToken: '',
            devilTokenCount: 0,

          //----- Side Content -----//
            prevLoading: true,
            moodLoading: true,
            modalLoading: true,

            test: '',
            moods: [],
            avatars: [],

            identityMood: '',
            moodSelected: '',

            identityLabel: '',
            avatarSelected: [],

            avatarIndex: 0,

          //----- Main Content -----//
            postLoading: true,
            postNoContent: false,

            posts: [],

            loadPostOffset: 0,
            loadingMore: false,

          //----- Post Modal -----//
            postModal: false,

            categories: [],
            sources: [],
            category: [],

            description: textareaNotice,
            source: '',

        },
        methods: {
            openFbLoginDialog () {
               FB.login(function(response) {
                   console.log(response);
                }, { scope: 'email' })
            },
          //----- Side Content -----//
            moodChange: function () {
              this.identityMood = this.moodSelected;
            },

            avatarChange: function (action) {
              this.prevLoading = true;

              var arrLen = this.avatars.length;
              var maxArrIndex = arrLen - 1;
              // this.avatarIndex= Math.floor(Math.random() * arrLen);

              if(action == 0){
                if(this.avatarIndex == 0){
                  this.avatarIndex = maxArrIndex;
                } else {
                  this.avatarIndex = this.avatarIndex - 1;
                }
              } else if (action == 1) {
                if(this.avatarIndex == maxArrIndex){
                  this.avatarIndex = 0;
                } else {
                  this.avatarIndex = this.avatarIndex + 1;
                }
              }

              this.avatarSelected = this.avatars[this.avatarIndex];
              this.identityLabel =  this.avatars[this.avatarIndex].label;

              this.prevLoading = false;
          },

          //----- Main Content -----//
            timeAgo: function (dateTime) {
              return timeago().format(dateTime);
            },

          //----- Post Modal -----//
            showPostModal: function () {
              $('.ui.basic.modal').modal('setting', {autofocus: false}).modal('show'); // TODO: Emit semantic-ui jquery modal
            },
            closePostModal: function () {
              $('.ui.basic.modal').modal('hide'); // TODO: Emit semantic-ui jquery modal
            },
            clearNotice: function () {
              if( this.description === textareaNotice ) {
                this.description = '';
              }
            },
            showNotice: function () {
              if( this.description === '' ) {
                this.description = textareaNotice;
              }
            },
            postSubmit: function (event) {
              this.errors = [];

              if( this.category.length <= 0 ) {
                //this.errors.push('Please select at least one category.');
              }
              if( ! this.description || this.description === textareaNotice) {
                this.errors.push('Post description should not be empty.');
              } else if( this.description.length < 15 ) {
                this.errors.push('Please describe your confession further.');
              }
              if( this.devilTokenCount >= 3) {
                this.errors.push('You have exceeded daily post limit = 3. Please come back tomorrow.');
              }

              if( this.errors.length > 0) {
                alert(this.errors.join(' '));
              } else {
                this.$http.post('/dt/api/posts/POST/', {
                  author_name: this.identityMood + ' ' + this.identityLabel,
                  author_avatar: this.avatarSelected.filename,
                  description: this.description,
                  source: this.source,
                  // quote_id: this.quote_id,
                  user_id: this.user.id,
                })
                  .then(function(response){
                    alert('Your confession #post-id has been posted.')
                    console.log(response)
                    event.target.reset()
                    this.description = ''
                    this.source = ''
                    this.category = []

                    if( this.devilTokenCount <= 3 ) {
                      this.devilTokenCount = ++this.devilTokenCount;
                    }

                    if( this.devilTokenCount >= 3 ) {
                      this.devilToken = 'You have no more devil token.';
                    } else if( this.devilTokenCount == 2 ) {
                      this.devilToken = 'You have 1 more devil token.';
                    } else {
                      this.devilToken = 'You have ' + (3 - this.devilTokenCount) + ' devil tokens.';
                    }

                  })
                  .catch(function(error){
                      alert('Post Submit: Unexpected error occurred. Please send feedback to CT admin.')
                      console.log(error)
                  });
              }
            },
            infiniteHandler($state) {
              setTimeout(() => {
                const temp = [];
                for (let i = this.list.length + 1; i <= this.list.length + 20; i++) {
                  temp.push(i);
                }
                this.list = this.list.concat(temp);
                $state.loaded();
              }, 1000);
            },
            loadMore: function () {
              this.loadPostOffset = this.loadPostOffset + 10;
              this.loadingMore = true;

              var getPosts = axios.get('/dt/api/posts/GET/' {
                limit: 10,
                offset: this.loadPostOffset,
              })
                .then( (result) => {
                  for (var i = 0, len = result.data.length; i < len; i++) {
                    this.posts.push(result.data[i]);
                    this.loadingMore = false;
                  }
                })
                .catch( (error) => {
                  console.error(error);
                });
            }
        },
        components: {
          BeatLoader,
          ClipLoader
        },
        beforeCreate: function () {
          //----- Global -----//
            var self = this;

            setTimeout(function() {
              new Fingerprint2().get(function(hash, components) {
                self.$http.post('/dt/api/user/GET/', {
                  bfp_hash: hash,
                  bfp_components: components
                })
                  .then(function(response){
                    console.log(response)
                    self.user = response.data[0]
                    self.modalLoading = false

                    if( self.user.post_count >= 3 ) {
                      self.devilToken = 'You have no more devil token.';
                    } else if( self.user.post_count == 2 ) {
                      self.devilToken = 'You have 1 more devil token.';
                    } else {
                      self.devilToken = 'You have ' + (3 - self.user.post_count) + ' devil tokens.';
                    }

                    this.devilTokenCount = self.user.post_count;
                  })
                  .catch(function(error){
                    console.log(error)
                  });
              })
            }, 3000)
        },
        mounted: function () {
          //----- Global -----//
            var self = this

          //----- Side Content -----//
            var getMoods = axios.get('/dt/index.php/home/get_options/mood')
              .then(function(result){
                self.moods = result.data;
                self.moodLoading = false;

                var moodRandom = Math.floor( Math.random() * result.data.length );
                self.identityMood = result.data[moodRandom].value;
                self.moodSelected = result.data[moodRandom].value;
              })
              .catch(function(error){
                console.error(error);
              });

            var getAvatars = axios.get('/dt/index.php/home/get_options/avatar')
              .then(function(result){
                self.avatars = result.data;

                var avatarRandom = Math.floor( Math.random() * result.data.length );
                self.identityLabel = result.data[avatarRandom].label;
                self.avatarSelected = result.data[avatarRandom];
                self.avatarIndex= avatarRandom;

                self.prevLoading = false;
              })
              .catch(function(error){
                console.error(error);
              });

            var getPosts = axios.get('/dt/api/posts/get/',
            {
              limit: 20,
            })
              .then(function(result){
                self.posts = result.data;
                self.postLoading = false;

                if(self.posts.length === 0) {
                  self.postNoContent = true;
                } else {
                  self.postNoContent = false;
                }
                console.log(result.data);
              })
              .catch(function(error){
                console.error(error);
              });

          //----- Main Content -----//

          //----- Post Modal -----//
            var getCategories = axios.get('/dt/index.php/home/get_options/category')
              .then(function(result){
                self.categories = result.data;
              })
              .catch(function(error){
                console.error(error);
              });

            var getSources = axios.get('/dt/index.php/home/get_options/source')
              .then(function(result){
                self.sources = result.data;
              })
              .catch(function(error){
                console.error(error);
              });
        }
      })

      $('.ui.checkbox').checkbox();
      $('.ui.dropdown').dropdown();

      function storeUserSession() {
          var identityMood = $('#identity-mood').text();
          var identityLabel = $('#identity-label').text();

          var userInfo = [
            {
              identity: identityMood + ' ' + identityLabel,
            }
          ];

          localStorage.setItem('dtUserInfo', JSON.stringify(userInfo));
      }

      storeUserSession();

      $('#identity-name').bind('DOMSubtreeModified', function() {
        storeUserSession();
      });
    </script>
  </body>
</html>