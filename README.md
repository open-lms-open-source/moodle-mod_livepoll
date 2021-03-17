# Live poll

This module allows having a live updating poll.

It uses Firebase to push the updates and stores the data anonymously, so no GDPR
concerns there.

## Configuration

1. Go to [Firebase](https://firebase.google.com/) and create an account
2. Once in the [Firebase console](https://console.firebase.google.com), create a project
3. We're using the __real time database__ for this project. So, create a database.
4. A popup will appear to configure _Cloud Firestore_, select any type of security (Locked/Test) mode.
5. Switch from __Cloud firestore__ to __Realtime database__ (Top part of the page)
6. Access __Rules__ and set these rules:
    ![firebase-rules](https://user-images.githubusercontent.com/1523388/53123057-32dec380-3526-11e9-8783-66626742e07a.png)
    ```
    {
        "rules": {
            ".read": "auth != null",
            ".write": "auth != null"
        }
    }
    ```
7. [Enable anonymous authentication](https://firebase.google.com/docs/auth/web/anonymous-auth), if anonymous authentication is not setup, the poll will not work.
8. From the project overview page in the [Firebase console](https://console.firebase.google.com),
click __Add Firebase to your web app__. If your project already has an app, select __Add App__
from the project overview page.
9. You can copy and paste the __API Key__, __Auth Domain__, __Database URL__ and the __Project ID__ to add
them to the Moodle plugin settings page. _Site administration > Plugins > Activity modules > Live poll_

## Usage

Just use it like a normal activity. You can create a poll in a course and configure the voting options and what to render.

It is suggested you use this when performing live classes so students and instructors can interact with the charts and controls live.

This will not store any of the responses into Moodle.

![livepolldemo](https://user-images.githubusercontent.com/1523388/53187047-3dee2e00-35d0-11e9-98bd-5005f4b7bd1c.gif)

## License

Copyright (c) 2018 Open LMS (https://www.openlms.net)

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <http://www.gnu.org/licenses/>.
