CREATE database helloprint;
\c helloprint
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE TABLE requests (
                          id serial PRIMARY KEY,
                          uuid uuid DEFAULT uuid_generate_v4 (),
                          token VARCHAR ( 50 ) UNIQUE NOT NULL,
                          message TEXT,
                          created_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
