<template>
    <div id="main">
        <header id="header">
            <!-- <h1>
                <router-link :to="{name: 'home'}">
                     
                </router-link>
            </h1> -->
            <navigationMenu></navigationMenu>
        </header>
        <div id="content" style="padding-top: 100px; padding-bottom: 100px">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                              <div class="card-header">
                                Laravel Vue 
                              </div>
                              <div class="card-body">
                                <h5 class="card-title blod">Good Day</h5>
                                <p class="card-text">Thank you for giving me this Opportunity to take this test 
                                    <br>i just have a problem with the vue part 
                                    <br>i am trying to implement an auth system with @websanova/vue-auth and a bug is kill me right now...
                                    <br>So please accept my test with the code base on vue part.</p>
                                    <br><br><br>
                                    The api part works well and i have done some deep writing from the area of:
                                    <br>traits, transformers, jwt auth, Exception handlers and Responser
                                </p>
                                <div>
                                    <table class="table table-hover">
                                      <thead>
                                        <tr>
                                          <th scope="col"></th>
                                          <th scope="col">Method</th>
                                          <th scope="col"> Endpoint</th>
                                          <th scope="col">Name</th>
                                          <th scope="col">Action</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <th scope="row">1</th>
                                          <td>Post</td>
                                          <td>api/login</td>
                                          <td>login</td>
                                          <td>App\Http\Controllers\Auth\AuthController@login</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">2</th>
                                          <td>Post</td>
                                          <td>api/logout</td>
                                          <td>logout</td>
                                          <td>App\Http\Controllers\Auth\AuthController@login</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">3</th>
                                          <td >GET|HEAD</td>
                                          <td>api/post</td>
                                          <td>post.index</td>
                                          <td>App\Http\Controllers\Post\PostController@index</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">4</th>
                                          <td >POST</td>
                                          <td>api/post</td>
                                          <td>post.store</td>
                                          <td>App\Http\Controllers\Post\PostController@store</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">5</th>
                                          <td>GET|HEAD</td>
                                          <td>api/post/{post}</td>
                                          <td>post.show</td>
                                          <td>App\Http\Controllers\Post\PostController@show</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">6</th>
                                          <td>POST</td>
                                          <td>api/refresh</td>
                                          <td>refresh</td>
                                          <td>App\Http\Controllers\Auth\AuthController@refresh</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">7</th>
                                          <td>POST</td>
                                          <td>api/user</td>
                                          <td>user.store</td>
                                          <td>App\Http\Controllers\User\UserController@store</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">8</th>
                                          <td >GET|HEAD</td>
                                          <td>api/user</td>
                                          <td>user.index</td>
                                          <td>App\Http\Controllers\User\UserController@index</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">9</th>
                                          <td>GET|HEAD</td>
                                          <td>api/user-profile</td>
                                          <td>profile</td>
                                          <td>App\Http\Controllers\Auth\AuthController@userProfile</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">10</th>
                                          <td>GET|HEAD</td>
                                          <td>api/user/verify/{token}</td>
                                          <td>verify</td>
                                          <td>App\Http\Controllers\User\UserController@verify</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">11</th>
                                          <td>GET|HEAD</td>
                                          <td>api/user-profile</td>
                                          <td>profile</td>
                                          <td>App\Http\Controllers\Auth\AuthController@userProfile</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">12</th>
                                          <td>DELETE</td>
                                          <td>api/user/{user}</td>
                                          <td>user.destroy</td>
                                          <td>App\Http\Controllers\User\UserController@destroy</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">13</th>
                                          <td>GET|HEAD</td>
                                          <td>api/user/{user}</td>
                                          <td>user.show</td>
                                          <td>App\Http\Controllers\User\UserController@show</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">14</th>
                                          <td>GET|HEAD</td>
                                          <td>api/user/{user}</td>
                                          <td>user.update</td>
                                          <td>App\Http\Controllers\User\UserController@update</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">15</th>
                                          <td>POST</td>
                                          <td>api/user/{user}/post</td>
                                          <td>post.create</td>
                                          <td>App\Http\Controllers\Post\PostController@store</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">16</th>
                                          <td>DELETE</td>
                                          <td>api/user/{user}/post/{post}</td>
                                          <td>post.delete</td>
                                          <td>App\Http\Controllers\Post\PostController@destroy</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">17</th>
                                          <td>PUT</td>
                                          <td>api/user/{user}/post/{post}</td>
                                          <td>post.update</td>
                                          <td>App\Http\Controllers\Post\PostController@update</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">18</th>
                                          <td>PUT|PATCH</td>
                                          <td>api/user/{user}/resend</td>
                                          <td>verifyResend</td>
                                          <td>App\Http\Controllers\User\UserController@resend</td>
                                        </tr>
                                        <tr>
                                          <th scope="row">19</th>
                                          <td>GET|HEAD</td>
                                          <td>{any?}</td>
                                          <td>profile</td>
                                          <td>Closure</td>
                                        </tr>
                                      </tbody> 
                                    </table>
                                </div>
                              </div>
                              <div class="card-footer text-muted text-center">
                                Thank You God bless @ James Adakole
                              </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
  import navigationMenu from './components/Menu.vue'
  export default {
    data() {
      return {
        //
      }
    },
    components: {
      navigationMenu
    }
  }
</script>