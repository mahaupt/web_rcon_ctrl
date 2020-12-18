# Database Layout

## Database Tables
### statuseffects
- id
- orderid (order of elements displayed on page)
- tab (frontend tab id)
- name (description of item)
- cmd (command to be sent via rcon)
- price (the 'price', currently timeout in seconds)

### spawntimeout
- id
- userid
- timeout

## SQL

```
CREATE TABLE statuseffects (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  orderid INT DEFAULT(10) NOT NULL,
  tab INT DEFAULT(0) NOT NULL,
  name VARCHAR(255) NOT NULL,
  cmd VARCHAR(255) NOT NULL,
  price INT DEFAULT(60) NOT NULL
  );

CREATE TABLE spawntimeout (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  userid INT NOT NULL,
  timeout INT NOT NULL
  );
```
