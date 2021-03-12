-- SURVEY MAIN TABLE --
DELETE FROM survey WHERE surveyId = 1;

INSERT INTO `survey` VALUES (1,'Street Rider Helmets','Street Rider Helmets',NULL,NULL,'std','on',NULL,3,NULL,NULL,NULL,1000,2000);

-- SURVEY INTRO TABLE --
DELETE FROM surveyIntro WHERE surveyId = 1;

insert into surveyIntro (surveyId,intro)
VALUES (1,"<div><h2>Street Rider Helmets</h2><table><tr><td><p>We're interested in hearing about your favorite street riding helmet.</p><p>By answering a few quick questions about your helmet, we will be able to provide for all OutspokenNinja users, your expert opinion on what makes it so good!</p><p>Maybe you have new helmet product, or even a prototype you'd like to discuss.  The Outspoken Ninja wants to be the place where you share your thoughts.</p></td><td><img src='/assets/helmet.generic.bw-small.png' width=180 height=180 /></td></tr></table></div>");

-- SURVEY QUESTION TABLE --
-- | questionId | int(11) | NO   | PRI | NULL    |       |
-- | surveyId   | int(11) | NO   | MUL | NULL    |       |
-- | qNumber    | int(11) | NO   | MUL | NULL    |       |
-- | qOrder     | int(11) | NO   |     | NULL    |       |
-- | qText      | text    | NO   |     | NULL    |       |
-- | qCss       | text    | YES  |     | NULL    |       |
-- | qJs        | text    | YES  |     | NULL    |       |
-- | pageId     | int(11) | NO   | MUL | NULL    |       |

DELETE FROM surveyQuestion WHERE surveyId = 1;  -- remove all existing questions

INSERT INTO surveyQuestion (questionId,surveyId,qNumber,qOrder,qText,qCss,qJs,pageId)
VALUES 
(
  1,1,1,1,'Helmet Fit:','','',1
),
(
  2,1,2,2,'Noise:','','',1
),
(
  3,1,3,3,'Visor Replacement:','','',1
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

DELETE surveyAnswers.* FROM surveyAnswers JOIN surveyQuestion ON surveyAnswers.questionId=surveyQuestion.questionId WHERE surveyQuestion.surveyId = 1;
 
INSERT INTO surveyAnswers (answerId,questionId,aNumber,aOrder,aText,aCss,aJs,aType,aName,aConfig)
VALUES 
(
   1,1,1,1,'','','','starselect','helmet_fit',''
),
(
   2,2,2,2,'','','','starselect','helmet_noise',''
),
(
   3,3,3,3,'','','','starselect','helmet_visor_replacement',''
)

;
