# ict2214-A01-G10

# Protect The Flag (PTF) - Cybersecurity Training Platform

Protect the Flag (PTF) is a cybersecurity platform designed to empower developers to enhance their skills in safeguarding web applications against injection attacks, particularly focusing on OWASP Top 10 vulnerabilities like Cross-site Scripting (XSS) and Structured Query Language Injection (SQLi). This repository contains the documentation and implementation details of the PTF platform.

## Abstract

Effective practical cybersecurity training is crucial to combat the increasing frequency of cyber-attacks. Protect The Flag (PTF) proposes a platform that allows users to assess their secure coding proficiency in a sandbox environment. Participants engage in tasks and simulated attacks to complete challenges, fostering hands-on learning experiences and skill development in injection attack mitigation.

## Proposed Solution

PTF's core objective is twofold: to educate users on crafting and defending against injection attacks and to equip developers with the knowledge and skills to implement effective defensive measures. The platform provides a safe environment for experimentation, allowing users to understand the impacts of attacks and practice defensive techniques progressively through interactive exercises and challenges.

## Solution Design

PTF utilizes a comprehensive system architecture, incorporating Docker containers for isolated challenge environments, Apache/PHP stack for the main site, and various tools and platforms for interactive learning experiences. The design emphasizes security and usability, ensuring that users can engage with challenges effectively while maintaining the integrity of the main website.

## Solution Implementation

The implementation of PTF involves setting up hardware servers, securing the main site and Docker containers, and creating scripts to manage Docker instances efficiently. Security measures include port filtering, HTTPS encryption, and user authentication protocols to safeguard against potential threats. Docker instances are rotated to ensure a clean slate for each user session, enhancing security and preventing exploitation of vulnerabilities.

## Results and Insights

PTF has been tested with participants, gathering feedback to improve usability and effectiveness. Users appreciated the speed and isolation of Docker containers for practicing safe coding techniques. Suggestions for future updates include adding features to assess code security and introducing more challenges focusing on specific areas of attacks.

---

With Protect The Flag, developers can enhance their cybersecurity skills and defend against injection attacks effectively. Join us in fortifying web applications and combating cyber threats.