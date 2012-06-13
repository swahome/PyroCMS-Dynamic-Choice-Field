PyroCMS-Dynamic-Choice-Field
============================

Create a dynamic choice field for your PyroCMS 2.1 where the list is populated from the database

Usage
=====

Place the dynamic choice folder in addons/shared_addons/field_types or to your specific site addons
go to admin/users and select Profile fields
Select add new profile Field admin/users/fields/create
on the Field Type select Dynamic Choices

The parameters
==============
Module - refers to the name of the module to load the model from e.g blog for blog module
Module Model  - refers to the model to load e.g blog_m this should enable loading model using module/model_module
Module Field - the numeric field to store e.g id for blog module (Will be the value for option list) should be db_column
Module Field Label - the text for the option field e.g title for the blog module should be the db_column 

Remarks
=======

Thanks for taking time to use or view this addition from me i do love pyrocms I am sure I will be adding numerous
contributions to this project and this is just a start.

You can view the thread on: http://www.pyrocms.com/forums/topics/view/18255

I still experience some issues with the custom field types so use this project with care I will give no guarantees.
