#!/bin/bash

#rm -rf ~/.java/.userPrefs/prefs.xml
#rm -rf ~/.java/.userPrefs/jetbrains/prefs.xml

# Reset PyCharm
rm -rf ~/.config/JetBrains/PyCharm*/eval
# rm -rf ~/.config/JetBrains/PyCharm*/options/other.xml
sed -i -E 's/<property name=\"evl.*\".*\/>//' ~/.config/JetBrains/PyCharm*/options/other.xml
rm -rf ~/.java/.userPrefs/jetbrains/pycharm

# Reset IntelliJ IDEA
rm -rf ~/.config/JetBrains/IntelliJIdea*/eval
# rm -rf ~/.config/JetBrains/IntelliJIdea*/options/other.xml
sed -i -E 's/<property name=\"evl.*\".*\/>//' ~/.config/JetBrains/IntelliJIdea*/options/other.xml
rm -rf ~/.java/.userPrefs/jetbrains/idea

# Reset WebStorm
rm -rf ~/.config/JetBrains/WebStorm*/eval
# rm -rf ~/.config/JetBrains/WebStorm*/options/other.xml
sed -i -E 's/<property name=\"evl.*\".*\/>//' ~/.config/JetBrains/WebStorm*/options/other.xml
rm -rf ~/.java/.userPrefs/jetbrains/webstorm
