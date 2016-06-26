require 'sinatra'
require 'sinatra/reloader'
require 'active_record'

require './models/users'

enable :sessions
use Rack::Session::Cookie

get '/' do
  @page_title = 'Side View Share'
  if session[:user_id]
    @user = User.find_by_id(session[:user_id])
  end

  erb :index
end

post '/users/create' do
  user = User.new(username: params[:username], password: params[:password])
  user.save!

  session[:user_id] = user.id
  redirect '/'
end

get '/signup' do
  @page_title = 'Sign up'
  erb :signup
end

get '/signin' do
  @page_title = 'Sign in'
  erb :signin
end

post '/signin' do
  user = User.find_by_username(params[:username])
  if user.password == params[:password]
    session[:user_id] = user.id
    redirect '/'
  else
    redirect '/signin'
  end
end

get '/signout' do

  redirect '/'
end
