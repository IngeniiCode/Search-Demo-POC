-- SURVEY MAIN TABLE --
DELETE FROM survey WHERE surveyId = 99;

INSERT INTO `survey` VALUES (99,'Ninja Site Survey','Ninja Site Survey',NULL,NULL,'std','on',NULL,3,NULL,NULL,NULL,NULL,NULL);

-- SURVEY INTRO TABLE --
DELETE FROM surveyIntro WHERE surveyId = 99;

insert into surveyIntro (surveyId,intro)
VALUES (99,"<div><h2>Outspoken Ninja Site Survey</h2><table><tr><td><p>Now that you have seen the site, we want to know what you think!.</p><p>By answering a few quick questions about this new site, we hope to make it better, more usable and <em>FUN!</em>!</p></td><td><img src='/assets/outspokenninja.png' width=180 height=180 /></td></tr></table></div>");

-- SURVEY QUESTION TABLE --
-- | questionId | int(11) | NO   | PRI | NULL    |       |
-- | surveyId   | int(11) | NO   | MUL | NULL    |       |
-- | qNumber    | int(11) | NO   | MUL | NULL    |       |
-- | qOrder     | int(11) | NO   |     | NULL    |       |
-- | qText      | text    | NO   |     | NULL    |       |
-- | qCss       | text    | YES  |     | NULL    |       |
-- | qJs        | text    | YES  |     | NULL    |       |
-- | pageId     | int(11) | NO   | MUL | NULL    |       |

DELETE FROM surveyQuestion WHERE surveyId = 99;

INSERT INTO surveyQuestion (questionId,surveyId,qNumber,qOrder,qText,qCss,qJs,pageId)
VALUES 
(
  991,99,1,1,'Site Layout','','',1
),
(
  992,99,2,2,'Usability','','',1
),
(
  993,99,3,3,'Features','','',1
),
(
  994,99,4,4,'Function','','',1
),
(
  995,99,5,5,'What features do you like?','','',1
),
(
  996,99,6,6,'What features do not not like?','','',1
),
(
  997,99,7,7,'Can you suggest anything we should do differently?','','',1
),
(
  998,99,8,8,'How likely to return?','','',1
),
(
  999,99,9,9,'How likely to recommend?','','',1
),
(
  1999,99,10,10,'Notify me here when the Ninja is LIVE!','','',1
)
;

--  SURVEY ANSWERS TABLE --
-- | answerId   | int(11)                  | NO   | PRI | NULL    |       |
-- | questionId | int(11)                  | NO   | MUL | NULL    |       |
-- | aNumber    | int(11)                  | NO   | MUL | NULL    |       |
-- | aOrder     | int(11)                  | NO   | MUL | NULL    |       |
-- | aText      | text                     | NO   |     | NULL    |       |
-- | aCss       | text                     | YES  |     | NULL    |       |
-- | aJs        | text                     | YES  |     | NULL    |       |
-- | aType      | varchar(32)              | YES  | MUL | NULL    |       |
-- | aName      | varchar(128)             | YES  |     | NULL    |       |
-- | aConfig    | text                     | YES  |     | NULL    |       |

DELETE surveyAnswers.* FROM surveyAnswers JOIN surveyQuestion ON surveyAnswers.questionId=surveyQuestion.questionId WHERE surveyQuestion.surveyId = 99;
 
INSERT INTO surveyAnswers (answerId,questionId,aNumber,aOrder,aText,aCss,aJs,aType,aName,aConfig)
VALUES 
(
   991,991,1,1,'','','','starselect','site_layout',''
),
(
   992,992,2,2,'','','','starselect','site_usability',''
),
(
   993,993,3,3,'','','','starselect','site_features',''
),
(
   994,994,4,4,'','','','starselect','site_function',''
),
(
   995,995,5,5,'','','','textarea','site_features_like','cols=50 rows=4'
),
(
   996,996,6,6,'','','','textarea','site_features_dislike','cols=50 rows=4'
),
(
   997,997,7,7,'','','','textarea','site_do_different','cols=50 rows=4'
),
(
   998,998,8,8,'','','','starselect','site_return',''
),
(
   999,999,9,9,'','','','starselect','site_recommend',''
),
(
   1999,1999,10,10,'','','','text','my_email','size=50'
)
;
