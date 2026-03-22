# Reflexion

Mein Ziel mit dieser Case Study war es, eine Webapplikation "old-school" aufzubauen, also ohne Frameworks, die einem die Arbeit abnehmen. Ich wollte verstehen, wie diese Frameworks aufgebaut sind und funktionieren. Als Software Engineer, der beruflich viel mit PHP arbeitet, wollte ich die Sprache richtig lernen und nicht nur oberflächlich nutzen.

Ich habe bewusst alles selbst gebaut. Einen Router, der HTTP-Methoden und Pfade auf Controller abbildet. Ein Singleton-Pattern für die Datenbankverbindung mit PDO. Das Schema und die Seed-Daten als reine SQL-Skripte. Eine saubere Schichtenarchitektur mit Ports und Adapters. Genau diese Dinge erledigen Frameworks wie Laravel oder Symfony normalerweise automatisch. Indem ich sie selbst implementiert habe, verstehe ich jetzt warum es sie gibt, was sie konkret abstrahieren und wo ihre Vorteile liegen.

Besonders lehrreich war die Unterscheidung zwischen Page-Routes, die eine ganze HTML-Seite rendern, und Resource-Routes, die ein HTML-Fragment für HTMX liefern (z.B. `/api/airports`). Das hat mir gezeigt, wie eine Server-Side Rendered App mit partiellem DOM-Update funktioniert. Ein Zwischenschritt zwischen klassischem Full-Page-Reload und einer vollen SPA mit React oder Vue.

Genau das war meine Absicht: die Evolution nachvollziehen, die Webentwickler in den frühen 2010ern durchgemacht haben. Vom Server-gerenderten HTML über AJAX-Fragmente bis hin zu Client-Side-Frameworks. Erst wenn man diesen Weg selbst gegangen ist, kann man die Vor- und Nachteile wirklich beurteilen. Man versteht, warum React State Management braucht, warum SPAs Routing im Browser machen, und warum Frameworks wie Next.js jetzt wieder zum Server-Rendering zurückkehren.

Was ich mitnehme: Durch das eigene Bauen habe ich ein tiefes Verständnis für Infrastruktur, Architektur und die Entscheidungen hinter modernen Technologien gewonnen. Dieses Wissen macht mich zu einem besseren Entwickler. Ich kann den richtigen Stack und die richtigen Tools für den jeweiligen Anwendungsfall wählen, schnell verstehen wie Dinge funktionieren, und kreativ eigene Lösungen bauen.
