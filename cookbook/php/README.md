Cook Book for php Implementation
==


getTargetFromUnicity
--

This code example allows you to get the informations of a target (Guid, fields....following the model you can find on API documentation : https://v8.mailperformance.com/doc) giving his unicity criteria.

getTargetAndSendMessage
--

This code takes the id of a target (with the getTargetFromUnicity's informations) and send it a pre-created message (the idMessage) in your account.

postTarget
--

This code check if the target exist. If it is, the code will update the target with the new informations; or else the code will creat the new target with these informations.

creatTargetAndAddToSegment
--

This code check if the target exist. If it is not, it will create the target. Then the code will add the target to a segment (using the id-segment).

sendHTML
--

This code send a message like getTargetAndSendMessage. But you can customize the "html message", the "text message", the "subject of the message", the mail "from address" and the "reply to" address.

T8
--

This code converted the html links in a text into T8 links.

createField
--

This code can create a new field or update an existing.

createValueList
--

This code can create a new value list or update an existing.

createCategory
--

This code create or update a category for a campaign.

duplicateAndValidate
--

This code add a target to a segment, then it duplicates an action, update the new action with the segment modified and finally test and validates the action.

Coming soon
--

Webhooks to listen clicks and openings.


Version
--

1.0 