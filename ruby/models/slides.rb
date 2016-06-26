ActiveRecord::Base.establish_connection(adapter: 'sqlite3', database: './db/slides.sqlite3')

class Slide < ActiveRecord::Base
end
