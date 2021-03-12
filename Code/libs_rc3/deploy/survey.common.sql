
-- SURVEY INTRO TABLE --
DELETE FROM survey WHERE surveyId = 1000;
insert into survey (surveyId,type,status)
VALUES
( 1000,'pre','on' )
;

DELETE FROM surveyIntro WHERE surveyId = 1000;

insert into surveyIntro (surveyId,intro)
VALUES 
( 1000,'' )
;

DELETE FROM surveyQuestion WHERE surveyId = 1000;  -- remove all existing questions

INSERT INTO surveyQuestion (questionId,surveyId,qNumber,qOrder,qText,qCss,qJs,pageId)
VALUES 
( 1001,1000,1,1,'Zip Code:','','',1 ),
( 1002,1000,2,2,'Product Brand:','','',1 ),
( 1003,1000,3,3,'Product Name:','','',1 ),
( 1004,1000,4,4,'Product Model:','','',1 ),
( 1005,1000,5,5,'Most Favorite Feature:','','',1 ),
( 1006,1000,6,6,'Least Favorite Feature:','','',1 )
;

DELETE surveyAnswers.* FROM surveyAnswers JOIN surveyQuestion ON surveyAnswers.questionId=surveyQuestion.questionId WHERE surveyQuestion.surveyId = 1000;
 
INSERT INTO surveyAnswers (answerId,questionId,aNumber,aOrder,aText,aCss,aJs,aType,aName,aConfig)
VALUES 
( 1001,1001,1,1,'','','','text','ninja_zip_code','size="10"' ),
( 1002,1002,1,1,'','','','text','product_brand','size="50"' ),
( 1003,1003,1,1,'','','','text','product_name','size="50"' ),
( 1004,1004,1,1,'','','','text','product_model','size="50"' ),
( 1005,1005,1,1,'','','','textarea','product_best_feature','rows=4 cols="50"' ),
( 1006,1006,1,1,'','','','textarea','product_worst_feature','rows=5 cols="50"' )
;

--
--  COMMON FOOTER FOR ALL SURVEYS 
--  
-- SURVEY INTRO TABLE --
DELETE FROM survey WHERE surveyId = 2000;
insert into survey (surveyId,type,status)
VALUES
( 2000,'post','on' )
;

DELETE FROM surveyIntro WHERE surveyId = 2000;

insert into surveyIntro (surveyId,intro)
VALUES 
( 2000,'' )
;

DELETE FROM surveyQuestion WHERE surveyId = 2000;  -- remove all existing questions

INSERT INTO surveyQuestion (questionId,surveyId,qNumber,qOrder,qText,qCss,qJs,pageId)
VALUES
( 2001,2000,1,1,'Overall Rating:','','',1 )
;

DELETE surveyAnswers.* FROM surveyAnswers JOIN surveyQuestion ON surveyAnswers.questionId=surveyQuestion.questionId WHERE surveyQuestion.surveyId = 2000;

INSERT INTO surveyAnswers (answerId,questionId,aNumber,aOrder,aText,aCss,aJs,aType,aName,aConfig)
VALUES
( 2001,2001,100,100,'','style="font-size: 4.0em;"','','starselect','product_rating_overall','' )
;


