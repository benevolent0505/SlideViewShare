require 'bcrypt'

ActiveRecord::Base.establish_connection(adapter: 'sqlite3', database: './db/users.sqlite3')
class User < ActiveRecord::Base
  include BCrypt

  def password
    @password ||= Password.new(password_hash)
  end

  def password=(new_password)
    @password = Password.create(new_password)
    self.password_hash = @password
  end
end
