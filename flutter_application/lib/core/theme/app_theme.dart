import 'package:flutter/material.dart';

// theme die das gleiche aussehen wie die webapp haben soll.
// die webapp ist ziemlich "brutalist": pink akzent, harte schwarze 1px
// borders, KEINE rounded corners, harter offset shadow ohne blur
class AppTheme {
  AppTheme._();

  // pink wie auf der webseite (style.css: --color-brand)
  static const Color brand = Color(0xFFFF00BF);
  static const Color brandDark = Color(0xFFCC0099);
  static const Color brandLight = Color(0xFFFF80DF);

  static const Color ink = Colors.black;
  static const Color surface = Color(0xFFF9FAFB); // tailwind gray-50
  static const Color textPrimary = Color(0xFF111827); // gray-900
  static const Color textMuted = Color(0xFF6B7280); // gray-500
  static const Color border = Colors.black;

  // schwarzer "stamp" shadow wie .card-hover auf der webseite
  static const List<BoxShadow> hardShadow = [
    BoxShadow(color: Colors.black, offset: Offset(4, 4), blurRadius: 0),
  ];

  static ThemeData light() {
    final scheme = ColorScheme.fromSeed(
      seedColor: brand,
      brightness: Brightness.light,
      primary: brand,
      onPrimary: Colors.white,
      surface: Colors.white,
    );

    // alle inputs sollen den gleichen schwarzen border ohne rundungen haben
    final inputBorder = OutlineInputBorder(
      borderRadius: BorderRadius.zero,
      borderSide: const BorderSide(color: Colors.black, width: 1),
    );

    return ThemeData(
      useMaterial3: true,
      colorScheme: scheme,
      scaffoldBackgroundColor: surface,
      // texte etwas fetter -> matched die uppercase tracking-wide labels auf
      // der webseite
      textTheme: Typography.blackMountainView.apply(
        bodyColor: textPrimary,
        displayColor: textPrimary,
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: Colors.white,
        foregroundColor: Colors.black,
        elevation: 0,
        centerTitle: false,
        titleTextStyle: TextStyle(
          color: Colors.black,
          fontSize: 16,
          fontWeight: FontWeight.w800,
          letterSpacing: 0.5,
        ),
        // bottom border wie navbar.php (border-b border-black)
        shape: Border(bottom: BorderSide(color: Colors.black, width: 1)),
      ),
      filledButtonTheme: FilledButtonThemeData(
        style: FilledButton.styleFrom(
          backgroundColor: brand,
          foregroundColor: Colors.white,
          shape: const RoundedRectangleBorder(borderRadius: BorderRadius.zero),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          textStyle: const TextStyle(
            fontWeight: FontWeight.w800,
            letterSpacing: 1.2,
            fontSize: 13,
          ),
          // uppercase ist nicht direkt im theme einstellbar, das machen wir
          // im widget mit Text("foo".toUpperCase())
        ),
      ),
      textButtonTheme: TextButtonThemeData(
        style: TextButton.styleFrom(
          foregroundColor: brandDark,
          textStyle: const TextStyle(fontWeight: FontWeight.w700),
          shape: const RoundedRectangleBorder(borderRadius: BorderRadius.zero),
        ),
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: Colors.white,
        border: inputBorder,
        enabledBorder: inputBorder,
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.zero,
          borderSide: const BorderSide(color: brand, width: 2),
        ),
        labelStyle: const TextStyle(color: textMuted, fontSize: 13),
        contentPadding:
            const EdgeInsets.symmetric(horizontal: 14, vertical: 14),
      ),
      // CardTheme reicht nicht weil wir den harten offset shadow brauchen.
      // -> machen wir in jedem widget selber mit Container + BoxDecoration
      cardTheme: const CardThemeData(
        color: Colors.white,
        elevation: 0,
        margin: EdgeInsets.symmetric(vertical: 6),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.zero,
          side: BorderSide(color: Colors.black, width: 1),
        ),
      ),
      navigationBarTheme: const NavigationBarThemeData(
        backgroundColor: Colors.white,
        indicatorColor: brand,
        indicatorShape: RoundedRectangleBorder(borderRadius: BorderRadius.zero),
        labelTextStyle: WidgetStatePropertyAll(
          TextStyle(
            fontSize: 11,
            fontWeight: FontWeight.w700,
            letterSpacing: 0.8,
          ),
        ),
        // border oben wie auf der webseite
        surfaceTintColor: Colors.white,
      ),
      dividerTheme: const DividerThemeData(
        color: Colors.black,
        thickness: 1,
        space: 1,
      ),
      switchTheme: SwitchThemeData(
        thumbColor: WidgetStatePropertyAll(brand),
        trackColor: WidgetStateProperty.resolveWith(
            (states) => states.contains(WidgetState.selected)
                ? brandLight
                : Colors.grey.shade300),
      ),
    );
  }
}

// reusable container der den "brutalist card" look macht.
// brauche ich überall: search-card, flight-card, booking-card, login-card
class BrutalCard extends StatelessWidget {
  const BrutalCard({
    super.key,
    required this.child,
    this.padding = const EdgeInsets.all(16),
    this.color = Colors.white,
    this.onTap,
  });

  final Widget child;
  final EdgeInsetsGeometry padding;
  final Color color;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    final box = Container(
      decoration: BoxDecoration(
        color: color,
        border: Border.all(color: Colors.black, width: 1),
        boxShadow: AppTheme.hardShadow,
      ),
      child: Padding(padding: padding, child: child),
    );
    if (onTap == null) return box;
    return InkWell(onTap: onTap, child: box);
  }
}

// kleine pill für status (sold out / available / confirmed / pending)
// matched die spans auf der webseite (bg-green-100 text-green-700 etc.)
class StatusPill extends StatelessWidget {
  const StatusPill({
    super.key,
    required this.label,
    required this.color,
    required this.background,
  });

  final String label;
  final Color color;
  final Color background;

  static const Color successText = Color(0xFF15803D);
  static const Color successBg = Color(0xFFDCFCE7);
  static const Color dangerText = Color(0xFFB91C1C);
  static const Color dangerBg = Color(0xFFFEE2E2);
  static const Color warnText = Color(0xFFA16207);
  static const Color warnBg = Color(0xFFFEF9C3);
  static const Color mutedText = Color(0xFF374151);
  static const Color mutedBg = Color(0xFFF3F4F6);

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
      color: background,
      child: Text(
        label,
        style: TextStyle(
          color: color,
          fontWeight: FontWeight.w700,
          fontSize: 11,
        ),
      ),
    );
  }
}
