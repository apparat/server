Request types
=============

Object list requests
--------------------

### Requests by date

Depending on the date precision configured via the environment variable `OBJECT_DATE_PRECISION` some of the more special date request types (by minuts, by seconds etc.) might not be available in your apparat instance.

Request to fetch all objects published in a **particular year** (or in any year):

```
GET /path/to/repo/2016
GET /path/to/repo/*
```

Request to fetch all objects published in a **particular month** (or in any month):

```
GET /path/to/repo/2016/06
GET /path/to/repo/2016/*
```

Request to fetch all objects published on a **particular day** (or on any day):

```
GET /path/to/repo/2016/06/02
GET /path/to/repo/2016/06/*
```

Request to fetch all objects published at a **particular hour** of the day (or at any hour):

```
GET /path/to/repo/2016/06/02/19
GET /path/to/repo/2016/06/02/*
```

Request to fetch all objects published at a **particular minute** of the hour (or at any minute):

```
GET /path/to/repo/2016/06/02/19/33
GET /path/to/repo/2016/06/02/19/*
```

Request to fetch all objects published at a **particular second** of the minute (or at any second):

```
GET /path/to/repo/2016/06/02/19/33/51
GET /path/to/repo/2016/06/02/19/33/*
```

Each of the date components may be replaced by the **wildcard** `*` to match all values for that particular component. So e.g. the following request will match all objects published at January 1st (regardless of the year):

```
GET /path/to/repo/*/01/01
```

### Requests by type, revision, visibilty or draft status

As long as no particular object ID is involved in a request — or rather, as long as a wildcard is used for the object ID component — a request will return a (potentially empty) list of objects. For the sake of clearness, the following examples use a date precision of `3` and wildcards for the three date components (year, month, day). 
  
Request to fetch all objects of a **particular type** (`article`):

```
GET /path/to/repo/*/*/*/*-article
```
  
Request to fetch all objects of a **particular revision** (`1`):

```
GET /path/to/repo/*/*/*/*/*-1
GET /path/to/repo/*/*/*/*-*/*-1
```
  
Request to fetch **hidden objects** only:

```
GET /path/to/repo/*/*/*/.*
```
  
Request to fetch **draft objects** only:

```
GET /path/to/repo/*/*/*/*/.*
GET /path/to/repo/*/*/*/*-*/.*
```
  
Object requests
---------------

As soon as a particular object ID is involved in a request it will result result in a single (or no) object:
  
```
GET /path/to/repo/*/*/*/123
GET /path/to/repo/*/*/*/123-article
GET /path/to/repo/*/*/*/.123
GET /path/to/repo/*/*/*/123/123
GET /path/to/repo/*/*/*/123/.123
GET /path/to/repo/*/*/*/123/123-1
GET /path/to/repo/*/*/*/123/123-1.md
```

As soon as the second object ID component is used, the ID **MUST** match the first occurrence or the request will fail: 
  
```
GET /path/to/repo/*/*/*/123/456
```